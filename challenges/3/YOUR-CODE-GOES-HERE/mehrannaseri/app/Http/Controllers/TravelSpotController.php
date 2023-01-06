<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Exceptions\ProtectedSpotException;
use App\Exceptions\SpotAlreadyPassedException;
use App\Http\Requests\TravelSpotStoreRequest;
use App\Http\Resources\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelSpot;
use Carbon\Carbon;

class TravelSpotController extends Controller
{
	public function arrived(Travel $travel, TravelSpot $spot)
	{
        $driver = auth()->user();
        if(! Driver::isDriver($driver))
            return response()->json([], 403);

        if($travel->status != TravelStatus::RUNNING)
            throw new InvalidTravelStatusForThisActionException();

        if($travel->driverHasArrivedToOrigin())
            throw new SpotAlreadyPassedException();

        $origin = $travel->getOriginSpot();
        $origin->update([
            'arrived_at' => Carbon::now()
        ]);

        return response()->json(TravelResource::make($travel));
	}

	public function store(Travel $travel, TravelSpotStoreRequest $request)
	{
        $user = auth()->user();

        if(Driver::isDriver($user))
            return response()->json([], 403);

        if($travel->allSpotsPassed())
            throw new SpotAlreadyPassedException();

        if($travel->status != TravelStatus::RUNNING)
            throw new InvalidTravelStatusForThisActionException();

        $travel->loadMissing('spots');

        $destination = $travel->spots()->where('position', $request->position)->first();
        if($destination){
            $destination->increment('position');
        }

        $travel->spots()->create($request->validated());

        return response()->json(TravelResource::make($travel));
	}

	public function destroy(Travel $travel, TravelSpot $spot)
	{
        $user = auth()->user();

        if(Driver::isDriver($user))
            return response()->json([], 403);

        if($travel->status != TravelStatus::RUNNING)
            throw new InvalidTravelStatusForThisActionException();

        if($travel->allSpotsPassed())
            throw new SpotAlreadyPassedException();

        $origin = $travel->getOriginSpot();
        if($origin->position == $spot->position)
            throw new ProtectedSpotException();

        $travel_spots = $travel->withMax('spots', 'position')->first();
        if($travel_spots->spots_max_position == $spot->position)
            throw new ProtectedSpotException();

        $travel->spots()->where('position', $spot->position)->delete();

        $travel->spots()->where('position', '>', $spot->position)->decrement('position');

        return response()->json(TravelResource::make($travel));
	}
}
