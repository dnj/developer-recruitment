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
use App\Models\Travel;
use App\Http\Requests\TravelStoreRequest;
use App\Models\TravelEvent;
use Illuminate\Http\Request;

class TravelController extends Controller
{
	public function view(Travel $travel)
	{
        return response()->json([
            'travel' => [
                'id' => $travel->id
            ]
        ]);
	}

	public function store(TravelStoreRequest $request)
	{
        $exist_active_travel = Travel::userHasActiveTravel(auth()->user());
        if($exist_active_travel) {
            throw new ActiveTravelException();
        }

        $travel = new Travel;

        $travel->passenger_id = auth()->id();
        $travel->status = TravelStatus::SEARCHING_FOR_DRIVER->value;

        $travel->save();

        foreach ($request->spots as $item) {
            $travel->spots()->create([
                'position' => $item['position'],
                'latitude' => $item['latitude'],
                'longitude' => $item['longitude'],
            ]);
        }

        return response()->json([
            'travel' => [
                'spots' => $request->spots,
                'passenger_id' => auth()->id(),
                'status' => TravelStatus::SEARCHING_FOR_DRIVER->value
            ]
        ], 201);
	}

	public function cancel(Travel $travel)
	{
        if(in_array($travel->status, [
            TravelStatus::CANCELLED,
            TravelStatus::DONE
        ])) {
            throw new CannotCancelFinishedTravelException();
        } else {
            if($travel->status == TravelStatus::RUNNING) {
                if($travel->passengerIsInCar() || ($travel->passenger_id == auth()->id())) {
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

	public function passengerOnBoard(int $travel_id)
	{
        $query = Travel::query()->with('events')->where('id', $travel_id);

        $travel = $query->first();

        if($travel instanceof Travel) {
            if($travel->passenger_id == auth()->id()) {
                abort(403);
            }

            if(!$travel->driverHasArrivedToOrigin()) {
                throw new CarDoesNotArrivedAtOriginException();
            }

            $found = false;
            foreach ($travel->events as $e) {
                if ($e->type == TravelEventType::PASSENGER_ONBOARD) {
                    $found = true;
                    break;
                }
            }

            if($found || ($travel->status == TravelStatus::DONE)) {
                throw new InvalidTravelStatusForThisActionException();
            }

            $travel->events()->create([
                'type' => TravelEventType::PASSENGER_ONBOARD->value
            ]);

            $travel = $query->first();

            return response()->json([
                'travel' => $travel->toArray()
            ]);
        } else {
            return response()->json([
                'code' => 'TravelNotFound'
            ], 400);
        }
	}

	public function done(int $travel_id)
	{
        $query = Travel::query()->with('events')->where('id', $travel_id);

        $travel = $query->first();

        if($travel instanceof Travel) {
            if($travel->passenger_id == auth()->id()) {
                abort(403);
            }

            if($travel->status == TravelStatus::DONE) {
                throw new InvalidTravelStatusForThisActionException();
            }

            if($travel->allSpotsPassed()) {
                if($travel->passengerIsInCar()) {
                    $travel->status = TravelStatus::DONE->value;
                    $travel->save();

                    $travel->events()->create([
                        'type' => TravelEventType::DONE->value
                    ]);

                    $travel = $query->first();

                    return response()->json([
                        'travel' => $travel->toArray()
                    ]);
                }
            } else {
                throw new AllSpotsDidNotPassException();
            }
        } else {
            return response()->json([
                'code' => 'TravelNotFound'
            ], 400);
        }
	}

	public function take(Travel $travel)
	{
        if(Travel::userHasActiveTravel(auth()->user())) {
            throw new ActiveTravelException();
        }

        if($travel->status == TravelStatus::CANCELLED) {
            throw new InvalidTravelStatusForThisActionException();
        }

        $travel->driver_id = auth()->id();
        $travel->save();

        $travel->events()->create([
            'type' => TravelEventType::ACCEPT_BY_DRIVER->value
        ]);

        return response()->json([
            'travel' => collect($travel->toArray())->filter(function (){
                return [
                    'id', 'driver_id', 'status'
                ];
            })
        ]);
	}
}
