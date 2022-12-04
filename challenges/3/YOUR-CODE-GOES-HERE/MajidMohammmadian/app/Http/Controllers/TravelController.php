<?php

namespace App\Http\Controllers;

use App\Enums\TravelEventType;
use App\Enums\TravelStatus;
use App\Models\Travel;
use App\Http\Requests\TravelStoreRequest;
use App\Models\TravelEvent;
use Illuminate\Http\Request;

class TravelController extends Controller
{
	public function view(int $travel)
	{
        $travel = Travel::query()->where('id', $travel)->first();

        if($travel instanceof Travel) {
            return response()->json([
                'travel' => [
                    'id' => $travel->id
                ]
            ]);
        } else {
            return response()->json([
                'code' => 'TravelNotFound'
            ], 400);
        }
	}

	public function store(TravelStoreRequest $request)
	{
        $exist_active_travel = Travel::userHasActiveTravel(auth()->user());
        if($exist_active_travel) {
            return response()->json([
                'code' => 'ActiveTravel'
            ], 400);
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

	public function cancel(int $travel)
	{
        $travel = Travel::query()->where('id', $travel)->first();

        if($travel instanceof Travel) {
            if(in_array($travel->status, [
                TravelStatus::CANCELLED,
                TravelStatus::DONE
            ])) {
                return response()->json([
                    'code' => 'CannotCancelFinishedTravel'
                ], 400);
            } else {
                if($travel->status == TravelStatus::RUNNING) {
                    if($travel->passengerIsInCar() || ($travel->passenger_id == auth()->id())) {
                        return response()->json([
                            'code' => 'CannotCancelRunningTravel'
                        ], 400);
                    }
                }

                $travel->status = TravelStatus::CANCELLED->value;
                $travel->save();

                return response()->json([
                    'travel' => $travel
                ]);
            }
        } else {
            return response()->json([
                'code' => 'TravelNotFound'
            ], 400);
        }
	}

	public function passengerOnBoard(int $travel)
	{
        $query = Travel::query()->with('events')->where('id', $travel);

        $travel = $query->first();

        if($travel instanceof Travel) {
            if($travel->passenger_id == auth()->id()) {
                return response()->json([
                    'code' => 'Forbidden'
                ], 403);
            }

            if(!$travel->driverHasArrivedToOrigin()) {
                return response()->json([
                    'code' => 'CarDoesNotArrivedAtOrigin'
                ], 400);
            }

            $found = false;
            foreach ($travel->events as $e) {
                if ($e->type == TravelEventType::PASSENGER_ONBOARD) {
                    $found = true;
                    break;
                }
            }

            if($found || ($travel->status == TravelStatus::DONE)) {
                return response()->json([
                    'code' => 'InvalidTravelStatusForThisAction'
                ], 400);
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

	public function done(int $travel)
	{
        $query = Travel::query()->with('events')->where('id', $travel);

        $travel = $query->first();

        if($travel instanceof Travel) {
            if($travel->passenger_id == auth()->id()) {
                return response()->json([
                    'code' => 'Forbidden'
                ], 403);
            }

            if($travel->status == TravelStatus::DONE) {
                return response()->json([
                    'code' => 'InvalidTravelStatusForThisAction'
                ], 400);
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
                return response()->json([
                    'code' => 'AllSpotsDidNotPass'
                ], 400);
            }
        } else {
            return response()->json([
                'code' => 'TravelNotFound'
            ], 400);
        }
	}

	public function take(int $travel)
	{
        if(Travel::userHasActiveTravel(auth()->user())) {
            return response()->json([
                'code' => 'ActiveTravel'
            ], 400);
        }

        $query = Travel::query()->with('events')->where('id', $travel);

        $travel = $query->first();

        if($travel instanceof Travel) {
            if($travel->status == TravelStatus::CANCELLED) {
                return response()->json([
                    'code' => 'InvalidTravelStatusForThisAction'
                ], 400);
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
        } else {
            return response()->json([
                'code' => 'TravelNotFound'
            ], 400);
        }
	}
}
