<?php

namespace App\Http\Controllers;

use App\Enums\TravelEventType;
use App\Enums\TravelStatus;
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

        list($can_not_cancel, $message) = $this->CanNotCancelTravel($travel);
        if($can_not_cancel){
            return response()->json(['code' => $message], 400);
        }
        else{
            $travel->update([
                'status' => TravelStatus::CANCELLED->value
            ]);

            return response()->json(TravelResource::make($travel));
        }



	}

	public function passengerOnBoard(Travel $travel)
	{
        $driver = auth()->user();
        $travel->loadMissing(['events', 'spots']);

        if(! Driver::isDriver($driver))
            return response()->json([], 403);

        if($travel->passengerIsInCar() or $travel->status != TravelStatus::RUNNING){
            return response()->json(['code' => 'InvalidTravelStatusForThisAction'], 400);
        }

        if(! $travel->driverHasArrivedToOrigin())
            return response()->json(['code' => 'CarDoesNotArrivedAtOrigin'], 400);

        $travel->events()->create([
            'type' => TravelEventType::PASSENGER_ONBOARD->value
        ]);

        return response()->json(TravelResource::make($travel));

	}

	public function done()
	{
	}

	public function take()
	{
	}

    private function CanNotCancelTravel($travel)
    {
        $user = auth()->user();
        $can_not_cancel = false;
        $message = '';

        if(in_array($travel->status->value, [TravelStatus::CANCELLED->value, TravelStatus::DONE->value])){
            $message = 'CannotCancelFinishedTravel';
            $can_not_cancel = true;
        }

        if($travel->status->value == TravelStatus::RUNNING->value
            and $travel->driverHasArrivedToOrigin() and ! Driver::isDriver($user) ){
            $message = 'CannotCancelRunningTravel';
            $can_not_cancel = true;
        }

        return [$can_not_cancel, $message];
    }
}
