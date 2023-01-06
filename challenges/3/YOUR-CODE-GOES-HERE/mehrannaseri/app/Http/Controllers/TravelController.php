<?php

namespace App\Http\Controllers;

use App\Enums\TravelEventType;
use App\Enums\TravelStatus;
use App\Exceptions\ActiveTravelException;
use App\Exceptions\AllSpotsDidNotPassException;
use App\Exceptions\CannotCancelFinishedTravelException;
use App\Exceptions\CannotCancelRunningTravelException;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Http\Requests\TravelStoreRequest;
use App\Http\Resources\TravelResource;
use App\Models\Driver;
use App\Models\Travel;

class TravelController extends Controller
{

	public function view(Travel $travel)
	{
        return response()->json(TravelResource::make($travel));
	}

	public function store(TravelStoreRequest $request)
	{
        $passenger = auth()->user();

        if(Travel::userHasActiveTravel($passenger))
            throw new ActiveTravelException();

        $travel = Travel::create([
            'passenger_id' => $passenger->id,
            'status' => TravelStatus::SEARCHING_FOR_DRIVER->value
        ]);

        $travel->spots()->createMany($request->spots);

        return response()->json(TravelResource::make($travel), 201);

	}

	public function cancel($travel_id)
	{
        $travel = Travel::findOrFail($travel_id);

        $this->CanNotCancelTravel($travel);

        $travel->update([
            'status' => TravelStatus::CANCELLED->value
        ]);

        return response()->json(TravelResource::make($travel));

	}

	public function passengerOnBoard(Travel $travel)
	{
        $driver = auth()->user();
        $travel->loadMissing(['events', 'spots']);

        if(! Driver::isDriver($driver))
            return response()->json([], 403);

        if($travel->passengerIsInCar() or $travel->status != TravelStatus::RUNNING){
            throw new InvalidTravelStatusForThisActionException();
        }

        if(! $travel->driverHasArrivedToOrigin())
            return response()->json(['code' => 'CarDoesNotArrivedAtOrigin'], 400);

        $travel->events()->create([
            'type' => TravelEventType::PASSENGER_ONBOARD->value
        ]);

        return response()->json(TravelResource::make($travel));

	}

	public function done(Travel $travel)
	{
        $driver = auth()->user();
        $travel->loadMissing(['spots', 'events']);

        if($travel->events->pluck('type')->contains(TravelEventType::DONE))
            throw new InvalidTravelStatusForThisActionException();

        if(! Driver::isDriver($driver))
            return response()->json([], 403);

        if(! $travel->allSpotsPassed())
            throw new AllSpotsDidNotPassException();

        $travel->events()->create(['type' => TravelEventType::DONE]);
        $travel->update(['status' => TravelStatus::DONE]);


        return response()->json(TravelResource::make($travel));
	}

	public function take(Travel $travel)
	{
        $driver  = auth()->user();

        if($travel->status == TravelStatus::CANCELLED)
            throw new InvalidTravelStatusForThisActionException();

        if($travel->userHasActiveTravel($driver))
            throw new ActiveTravelException();

        $travel->update(['driver_id' => $driver->id]);

        return response()->json(TravelResource::make($travel));
	}

    private function CanNotCancelTravel($travel)
    {
        $user = auth()->user();

        if($travel->passengerIsInCar())
            throw new CannotCancelRunningTravelException();

        if($travel->driverHasArrivedToOrigin() and ! Driver::isDriver($user))
            throw new CannotCancelRunningTravelException();

        if($travel->status->value == TravelStatus::DONE->value
            or $travel->status->value == TravelStatus::CANCELLED->value)
            throw new CannotCancelFinishedTravelException();
    }
}
