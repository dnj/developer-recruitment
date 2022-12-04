<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Exceptions\ProtectedSpotException;
use App\Exceptions\SpotAlreadyPassedException;
use App\Models\Travel;
use App\Models\TravelSpot;
use Carbon\Carbon;
use App\Http\Requests\TravelSpotStoreRequest;
use Illuminate\Http\JsonResponse;

class TravelSpotController extends Controller
{
    /**
     * @throws SpotAlreadyPassedException
     * @throws InvalidTravelStatusForThisActionException
     */
    public function arrived(Travel $travel, TravelSpot $spot): JsonResponse
    {
        $travel = $travel->with('spots')->first();

        if($travel->passenger_id == auth()->id()) {
            abort(403);
        }

        if($travel->status == TravelStatus::CANCELLED) {
            throw new InvalidTravelStatusForThisActionException();
        }

        $travel_arrived_exist = $travel->spots()->where('position', 0)->whereNotNull('arrived_at')->exists();

        if($travel->passenger_id == auth()->id() && $travel_arrived_exist) {
            abort(403);
        }

        if($travel_arrived_exist) {
            throw new SpotAlreadyPassedException();
        }

        $travel->spots()->where('position', 0)->update([
            'arrived_at' => Carbon::now()
        ]);

        return response()->json([
            'travel' => $travel->with('spots')->first()->toArray()
        ]);
	}

    /**
     * @throws SpotAlreadyPassedException
     * @throws InvalidTravelStatusForThisActionException
     */
    public function store(Travel $travel, TravelSpotStoreRequest $request): JsonResponse
    {
        $travel = $travel->with('spots')->first();

        if($travel->driver_id == auth()->id()) {
            abort(403);
        }

        if($travel->status == TravelStatus::CANCELLED) {
            throw new InvalidTravelStatusForThisActionException();
        }

        foreach ($travel->spots as $item) {
            if($item->position >= $request->position) {
                if(is_null($item->arrived_at)) {
                    $travel->spots()->where('position', $request->position)->increment('position');
                } else {
                    throw new SpotAlreadyPassedException();
                }
            }
        }
        $travel->spots()->create($request->toArray());

        return response()->json([
            'travel' => $travel->with('spots')->first()->toArray()
        ]);
	}

    /**
     * @throws SpotAlreadyPassedException
     * @throws InvalidTravelStatusForThisActionException
     * @throws ProtectedSpotException
     */
    public function destroy(Travel $travel, TravelSpot $spot): JsonResponse
    {
        $travel = $travel->with('spots')->first();

        if($travel->driver_id == auth()->id()) {
            abort(403);
        }

        if($travel->status == TravelStatus::CANCELLED) {
            throw new InvalidTravelStatusForThisActionException();
        }

        if(!is_null($spot->arrived_at)) {
            throw new SpotAlreadyPassedException();
        }

        if($spot->position == 0 || (count($travel->spots) == 2)) {
            throw new ProtectedSpotException();
        }

        $travel->spots()->where('position', $spot->position)->delete();

        foreach ($travel->spots as $item) {
            if($item->position > $spot->position) {
                if(is_null($item->arrived_at)) {
                    $travel->spots()->where('position', $item->position)->decrement('position');
                } else {
                    throw new SpotAlreadyPassedException();
                }
            }
        }

        $travel = $travel->with('spots')->first();

        return response()->json([
            'travel' => $travel->toArray()
        ]);
	}
}
