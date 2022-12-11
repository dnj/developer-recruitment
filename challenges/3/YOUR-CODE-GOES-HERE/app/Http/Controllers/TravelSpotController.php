<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use App\Http\Requests\TravelSpotStoreRequest;
use Illuminate\Http\Response;
use App\Enums\TravelStatus;
use App\Models\TravelSpot;
use App\Models\Travel;
use App\Models\Driver;
use App\Models\User;

class TravelSpotController extends Controller
{
	public function arrived(Travel $travel , TravelSpot $spot)
	{
        if(Driver::isDriver(auth()->user())){
            if($travel->status->value == TravelStatus::RUNNING->value && is_null($spot->arrived_at)){
                $spot->update(['arrived_at' => now()]);
                return response()->json([
                    'travel' => [
                        'spots' => [$spot]
                    ]
                ],HttpFoundationResponse::HTTP_OK);
            }elseif($travel->status->value == TravelStatus::CANCELLED->value ||
                    $travel->status->value == TravelStatus::RUNNING->value && !is_null($spot->arrived_at)
            ){
                return response()->json([
                    'code' => 'InvalidTravelStatusForThisAction' || 'SpotAlreadyPassed'
                ],HttpFoundationResponse::HTTP_BAD_REQUEST);
            }

        }else{
            return (new Response())->setStatusCode(
                HttpFoundationResponse::HTTP_FORBIDDEN
            );
        }
	}

	public function store(TravelSpotStoreRequest $request , Travel $travel)
	{
        if(Driver::isDriver(auth()->user())){
            return (new Response())->setStatusCode(
                HttpFoundationResponse::HTTP_FORBIDDEN
            );
        }else{
            $create_travel_spot =  TravelSpot::query()->create([
                'travel_id'  => $travel->id,
                'position'   => $request->position,
                'latitude'   => $request->latitude ,
                'longitude'  => $request->longitude,
                'arrived_at' => null
            ]);

            if(in_array($create_travel_spot->position , [0,1])){
                if($create_travel_spot->position == 0){
                    $travel_spot = TravelSpot::query()->where('travel_id' , $travel->id)->first();
                    $travel_spot->update(['position' => 1]);

                    return response()->json([
                        'travel' => [
                            'spots' => [$create_travel_spot]
                        ]
                    ],HttpFoundationResponse::HTTP_OK);
                }else{
                    if($travel->status->value == TravelStatus::CANCELLED->value){
                        return response()->json([
                            'code' => 'InvalidTravelStatusForThisAction'
                        ],HttpFoundationResponse::HTTP_BAD_REQUEST);
                    }

                    return response()->json([
                        'code' => 'SpotAlreadyPassed'
                    ],HttpFoundationResponse::HTTP_BAD_REQUEST);
                }

            }else{
                return response()->json([
                    'errors' => ['position' => '']
                ] , HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
	}

	public function destroy(Travel $travel , TravelSpot $spot)
	{
        if(Driver::isDriver(auth()->user())){
            return (new Response())->setStatusCode(
                HttpFoundationResponse::HTTP_FORBIDDEN
            );
        }else{
            if(is_null($spot->arrived_at)){
                if($travel->status->value == TravelStatus::RUNNING->value && $spot->position == 1){
                    return response()->json([
                        'travel' => [
                            'spots' => [$spot]
                        ]
                    ],HttpFoundationResponse::HTTP_OK);
                }elseif($travel->status->value == TravelStatus::RUNNING->value && $spot->position == 0 ||
                    $travel->status->value == TravelStatus::CANCELLED->value
                ){
                    return response()->json([
                        'code' => 'ProtectedSpot' || 'InvalidTravelStatusForThisAction'
                    ],HttpFoundationResponse::HTTP_BAD_REQUEST);
                }
            }else{
                if($travel->status->value == TravelStatus::RUNNING->value){
                    return response()->json([
                        'code' => 'SpotAlreadyPassed' || 'ProtectedSpot'
                    ],HttpFoundationResponse::HTTP_BAD_REQUEST);
                }
            }
        }
	}
}
