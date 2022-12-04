<?php

namespace App\Http\Controllers;

use App\Enums\TravelEventType;
use App\Enums\TravelStatus;
use App\Exceptions\ActiveTravelException;
use App\Exceptions\AllSpotsDidNotPassException;
use App\Exceptions\CannotCancelFinishedTravelException;
use App\Exceptions\CannotCancelRunningTravelException;
use App\Exceptions\CarDoesNotArrivedAtOriginException;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Http\Requests\TravelStoreRequest;
use App\Models\Travel;
use Illuminate\Http\JsonResponse;

class TravelController extends Controller
{
    public function view(Travel $travel): JsonResponse
    {
        return response()->json([
            'travel' => [
                'id' => $travel->id
            ]
        ]);
    }

    /**
     * @throws ActiveTravelException
     */
    public function store(TravelStoreRequest $request): JsonResponse
    {
        $exist_active_travel = Travel::userHasActiveTravel(auth()->user());
        if ($exist_active_travel) {
            throw new ActiveTravelException();
        }

        $travel = new Travel;

        $travel->passenger_id = auth()->id();
        $travel->status = TravelStatus::SEARCHING_FOR_DRIVER->value;

        $travel->save();

        foreach ($request->spots as $item) {
            $travel->spots()->create([
                'position'  => $item['position'],
                'latitude'  => $item['latitude'],
                'longitude' => $item['longitude'],
            ]);
        }

        return response()->json([
            'travel' => [
                'spots'        => $request->spots,
                'passenger_id' => auth()->id(),
                'status'       => TravelStatus::SEARCHING_FOR_DRIVER->value
            ]
        ], 201);
    }

    /**
     * @throws CannotCancelFinishedTravelException
     * @throws CannotCancelRunningTravelException
     */
    public function cancel(Travel $travel): JsonResponse
    {
        if (in_array($travel->status, [
            TravelStatus::CANCELLED,
            TravelStatus::DONE
        ])) {
            throw new CannotCancelFinishedTravelException();
        } else {
            if ($travel->status == TravelStatus::RUNNING) {
                if ($travel->passengerIsInCar() || ($travel->passenger_id == auth()->id())) {
                    throw new CannotCancelRunningTravelException();
                }
            }

            $travel->status = TravelStatus::CANCELLED->value;
            $travel->save();

            return response()->json([
                'travel' => $travel
            ]);
        }
    }

    /**
     * @throws InvalidTravelStatusForThisActionException
     * @throws CarDoesNotArrivedAtOriginException
     */
    public function passengerOnBoard(Travel $travel): JsonResponse
    {
        $travel = $travel->with('events')->first();

        if ($travel->passenger_id == auth()->id()) {
            abort(403);
        }

        if (!$travel->driverHasArrivedToOrigin()) {
            throw new CarDoesNotArrivedAtOriginException();
        }

        $found = false;
        foreach ($travel->events as $e) {
            if ($e->type == TravelEventType::PASSENGER_ONBOARD) {
                $found = true;
                break;
            }
        }

        if ($found || ($travel->status == TravelStatus::DONE)) {
            throw new InvalidTravelStatusForThisActionException();
        }

        $travel->events()->create([
            'type' => TravelEventType::PASSENGER_ONBOARD->value
        ]);

        return response()->json([
            'travel' => $travel->with('events')->first()->toArray()
        ]);
    }

    /**
     * @throws InvalidTravelStatusForThisActionException
     * @throws AllSpotsDidNotPassException
     */
    public function done(Travel $travel): JsonResponse
    {
        $travel = $travel->with('events')->first();

        if ($travel->passenger_id == auth()->id()) {
            abort(403);
        }

        if ($travel->status == TravelStatus::DONE) {
            throw new InvalidTravelStatusForThisActionException();
        }

        if ($travel->allSpotsPassed()) {
            if ($travel->passengerIsInCar()) {
                $travel->status = TravelStatus::DONE->value;
                $travel->save();

                $travel->events()->create([
                    'type' => TravelEventType::DONE->value
                ]);

                $travel = $travel->with('events')->first();

                return response()->json([
                    'travel' => $travel->toArray()
                ]);
            } else {
                throw new AllSpotsDidNotPassException();
            }
        } else {
            throw new AllSpotsDidNotPassException();
        }
    }

    /**
     * @throws InvalidTravelStatusForThisActionException
     * @throws ActiveTravelException
     */
    public function take(Travel $travel): JsonResponse
    {
        if (Travel::userHasActiveTravel(auth()->user())) {
            throw new ActiveTravelException();
        }

        if ($travel->status == TravelStatus::CANCELLED) {
            throw new InvalidTravelStatusForThisActionException();
        }

        $travel->driver_id = auth()->id();
        $travel->save();

        $travel->events()->create([
            'type' => TravelEventType::ACCEPT_BY_DRIVER->value
        ]);

        return response()->json([
            'travel' => collect($travel->toArray())->filter(function () {
                return [
                    'id', 'driver_id', 'status'
                ];
            })
        ]);
    }
}
