<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Http\Requests\TravelSpotStoreRequest;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use App\Models\Travel;
use App\Models\TravelSpot;

class TravelSpotController extends Controller
{
	public function arrived(Travel $travel)
	{
        $travels            = Travel::factory()->create(['status' => TravelStatus::RUNNING->value]);
        $travel_spot        = TravelSpot::factory(2)->create();
        $arrived            = TravelSpot::query()->where([
                'travel_id' => $travel->id
        ])->first();

        if(is_null($arrived->arrived_at)){
            $arrived->update(['position' => 1 , 'arrived_at' => now()]);

            if(!is_null($arrived->arrived_at)){
                return response()->json([
                    'code' =>'SpotAlreadyPassed'
                ],HttpFoundationResponse::HTTP_BAD_REQUEST);
            }
//            dd($arrived);


        }

//        if($travel->status->value == (TravelStatus::CANCELLED->value || TravelStatus::DONE->value )){
//            return response()->json([
//                'code' => 'InvalidTravelStatusForThisAction' || 'SpotAlreadyPassed'
//            ],HttpFoundationResponse::HTTP_BAD_REQUEST);
//        }

//        if(isset($arrived)){
//            return response()->json([
//                "travel" => [
//                    "spots" => $create_travel_spot
//                ]
//            ],HttpFoundationResponse::HTTP_OK);
//        }

	}

	public function store(TravelSpotStoreRequest $request , Travel $travel)
	{
        $create_travel_spot =  TravelSpot::query()->create([
            'travel_id' => $travel->id,
            'position'  => $request->position,
            'latitude'  => $request->latitude ,
            'longitude' => $request->longitude
        ]);

        if(!in_array($travel->passenger_id , Driver::query()->get('id')->toArray())){
            if(isset($create_travel_spot)){
                if(!in_array($create_travel_spot->position , [0,1])){
                    return response()->json([
                        'errors' => ['position' => '']
                    ] , HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
                }else{
                    if((new Travel())->allSpotsPassed() || (new Travel())->driverHasArrivedToOrigin()) {
                        return response()->json([
                            'code' => 'SpotAlreadyPassed' || 'InvalidTravelStatusForThisAction'
                        ], HttpFoundationResponse::HTTP_BAD_REQUEST);
                    }else{
                        return response()->json([
                            'travel' => [
                                'spots' => $create_travel_spot->get()
                            ]
                        ] , HttpFoundationResponse::HTTP_OK);
                    }
                }
            }
        }else{
            return response()->setStatusCode(
                HttpFoundationResponse::HTTP_FORBIDDEN
            );
        }
	}

	public function destroy(Travel $travel , TravelSpot $spot)
	{
        $travel_spot = TravelSpot::query()->where([
           'travel_id'  => $travel->id,
           'id'         => $spot->id
        ])->firstOrFail();

        if(!Driver::isDriver(User::query()->where('id' , $travel->passenger_id)->first())){
            if($travel_spot->delete() || is_null($travel_spot->arrived_at) ||
                $travel->status->value == TravelStatus::CANCELLED->value
            ){
                return response()->json([
                    'code' => 'ProtectedSpot' || 'SpotAlreadyPassed' || 'InvalidTravelStatusForThisAction'
                ] , HttpFoundationResponse::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'travel' => [
                    'spots' => $travel_spot->get()
                ]
            ] , HttpFoundationResponse::HTTP_OK);
        }else{
            return (new Response())->setStatusCode(
                HttpFoundationResponse::HTTP_FORBIDDEN
            );
        }
	}
}
