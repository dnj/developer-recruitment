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
use App\Http\Resources\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use Illuminate\Support\Facades\DB;

class TravelController extends Controller
{

    public function view()
    {
    }

    public function store(TravelStoreRequest $request)
    {
        if (Travel::userHasActiveTravel(auth()->user())) throw new ActiveTravelException();

        $spots = collect($request->get('spots'))->map(function ($item) {
            return [
                'position'  => $item['position'],
                'latitude'  => $item['latitude'],
                'longitude' => $item['longitude']
            ];
        });

        DB::transaction(function () use ($spots) {
            $travel = Travel::query()->create([
                'passenger_id' => auth()->id(),
                'status' => TravelStatus::SEARCHING_FOR_DRIVER->value
            ]);
            $travel->spots()->createMany($spots->all());
        });

        return response()->json([
            'travel' => [
                'spots' => $request->get('spots'),
                'passenger_id' => auth()->id(),
                'status' => TravelStatus::SEARCHING_FOR_DRIVER->value
            ]
        ], 201);
    }

    public function cancel(Travel $travel)
    {
        if (!auth()->user()->can('cancel', $travel)) abort(403);

        if (in_array($travel->status, [TravelStatus::CANCELLED, TravelStatus::DONE])) throw new CannotCancelFinishedTravelException();

        if ($travel->status == TravelStatus::RUNNING or $travel->passengerIsInCar() or $travel->allSpotsPassed()) throw new CannotCancelRunningTravelException();

        $travel->update(['status' => TravelStatus::CANCELLED->value]);

        return response()->json(TravelResource::make($travel));
    }

    public function passengerOnBoard(Travel $travel)
    {
        if (!Driver::isDriver(auth()->user())) abort(403);

        if (!auth()->user()->can('markAsPassengerOnBoard', $travel)) abort(403);

        if (!$travel->driverHasArrivedToOrigin()) throw new CarDoesNotArrivedAtOriginException();

        if ($travel->status == TravelStatus::DONE) throw new InvalidTravelStatusForThisActionException();

        $travel->events()->create([
            'type' => TravelEventType::PASSENGER_ONBOARD->value
        ]);

        return response()->json(TravelResource::make($travel));
    }

    public function done(Travel $travel)
    {
        if (!Driver::isDriver(auth()->user())) abort(403);

        if (!auth()->user()->can('markAsDone', $travel)) abort(403);

        if ($travel->status == TravelStatus::DONE or $travel->passengerIsInCar()) throw new InvalidTravelStatusForThisActionException();

        if (!$travel->allSpotsPassed()) throw new AllSpotsDidNotPassException();

        $travel->update(['status' => TravelStatus::DONE->value]);

        $travel->events()->create(['type' => TravelEventType::DONE->value]);

        return response()->json(TravelResource::make($travel));
    }

    public function take(Travel $travel)
    {
        if (Travel::userHasActiveTravel(auth()->user())) throw new ActiveTravelException();

        if ($travel->status != TravelStatus::SEARCHING_FOR_DRIVER) throw new InvalidTravelStatusForThisActionException();

        $driver = Driver::byUser(auth()->user());

        $travel->update(['driver_id' => $driver->id]);

        $travel->events()->create(['type' => TravelEventType::ACCEPT_BY_DRIVER->value]);

        return [
            'travel' => [
                'id' => $travel->id,
                'driver_id' => $driver->id,
                'status' => $travel->status->value
            ]
        ];
    }
}
