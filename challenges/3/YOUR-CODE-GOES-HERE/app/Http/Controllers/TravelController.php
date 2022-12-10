<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use App\Http\Requests\TravelSpotStoreRequest;
use App\Enums\TravelEventType;
use App\Enums\DriverStatus;
use App\Enums\TravelStatus;
use App\Models\TravelEvent;
use App\Models\TravelSpot;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\User;

class TravelController extends Controller
{

	public function view(Travel $travel) : object
	{
        return response()->json([
            'travel'  => $travel
        ],HttpFoundationResponse::HTTP_OK);
	}

	public function store(TravelSpotStoreRequest $request) : object
	{
        $spots = [];
        $create_travel = Travel::query()->create([
            "passenger_id"   => User::factory()->create()->id,
            "status"         => TravelStatus::SEARCHING_FOR_DRIVER->value
        ]);

        if(isset($create_travel)) {
            foreach ($request->all() as $form_data) {
                foreach ($form_data as $data) {
                    if (in_array($data['position'], [0, 1])) {

                        TravelSpot::query()->create([
                            "travel_id" => $create_travel->id,
                            "position" => $data['position'],
                            "latitude" => $data['latitude'],
                            "longitude" => $data['longitude']
                        ]);

                        $spots[] = $data;
                    } else {
                        return response()->json([
                            "errors" => [
                                "spots" => 'BadPositions'
                            ]
                        ], HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);
                    }
                }
            }
        }

//        $create_travel->update(['status' => TravelStatus::RUNNING->value]);
//        if($create_travel->status->value == TravelStatus::RUNNING->value){
//            return response()->json([
//                "code" => "ActiveTravel"
//            ], HttpFoundationResponse::HTTP_BAD_REQUEST);
//        }

        return response()->json([
            "travel" => [
                "spots"        => $spots,
                "passenger_id" => $create_travel->id,
                "status"       => $create_travel->status->value
            ]
        ], HttpFoundationResponse::HTTP_CREATED);
	}

	public function cancel(Travel $travel)
	{
        $check_travel  = Travel::query()->where('id' , $travel->id)->first();
        $cancel_stats  = TravelStatus::CANCELLED->value ;

        if($check_travel->status->value == ( TravelStatus::SEARCHING_FOR_DRIVER->value)){
            $check_travel->update(['status'  => $cancel_stats]);

            return response()->json([
                "travel" => [
                    "status"  => $cancel_stats
                ]
            ],HttpFoundationResponse::HTTP_OK);

        }elseif ($check_travel->status->value == ($cancel_stats || TravelStatus::DONE->value) ||
            (new Travel())->passengerIsInCar() ||  $cancel_stats
        ) {

            return response()->json([
                "code" => "CannotCancelFinishedTravel" || "CannotCancelRunningTravel" || "CannotCancelRunningTravel"
            ], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }
	}

	public function passengerOnBoard(Travel $travel)
	{
        if(Driver::isDriver(auth()->user())){

            if($travel->status->value == TravelStatus::DONE->value || !$travel->driverHasArrivedToOrigin()){
                return response()->json([
                    'code' => 'InvalidTravelStatusForThisAction' || 'CarDoesNotArrivedAtOrigin'
                ], HttpFoundationResponse::HTTP_BAD_REQUEST);
            }

            $travel_event = TravelEvent::query()->where('travel_id',$travel->id);
            $travel_event->first()->update(['type' => 'PASSENGER_ONBOARD']);
            if($travel->passengerIsInCar()){
                return response()->json([
                    "travel" => [
                        "events"  => $travel_event->get()
                    ]
                ],HttpFoundationResponse::HTTP_OK);
            }
        }else{
            return (new Response())->setStatusCode(
                HttpFoundationResponse::HTTP_FORBIDDEN
            );
        }
	}

	public function done(Travel $travel)
	{
        $check_travel  = Travel::query()->where(
            'id' , $travel->id
        )->first();

        $travel_events = TravelEvent::query()->where(
            'travel_id',$check_travel->id
        )->get() ;

        $travel_spot   = TravelSpot::query()->where([
            'travel_id'    => $travel->id
        ])->first();

        $done_status   = TravelStatus::DONE->value ;

        if($check_travel->status->value == TravelStatus::RUNNING->value){
            $check_travel->update(['status' => $done_status]);

            foreach ($travel_events as $even){
                $even->update(['type' => $done_status]);
            }

            return response()->json([
                "travel" => [
                    'status' => $done_status,
                    'events' => $travel_events
                ]
            ],HttpFoundationResponse::HTTP_OK);

        }elseif ($check_travel->status->value == TravelStatus::DONE->value){

            return response()->json([
                "code" => 'AllSpotsDidNotPass' || 'InvalidTravelStatusForThisAction'
            ],HttpFoundationResponse::HTTP_BAD_REQUEST);

        }
	}

	public function take(Travel $travel)
	{
        $check_travel     = Travel::query()->where('id' , $travel->id)->first();
        $searching_status = TravelStatus::SEARCHING_FOR_DRIVER->value ;

        if($check_travel->status->value == $searching_status){
            return response()->json([
                "travel" => [
                    'id'         => $check_travel->id,
                    'driver_id'  => $check_travel->driver_id,
                    'status'     => $searching_status,
                ]
            ],HttpFoundationResponse::HTTP_OK);

        }elseif ($check_travel->status->value == TravelStatus::CANCELLED->value ||
            (TravelStatus::RUNNING->value && !is_null($check_travel->driver_id))
        ){

            return response()->json([
                "code" => 'InvalidTravelStatusForThisAction' || 'ActiveTravel'
            ],HttpFoundationResponse::HTTP_BAD_REQUEST);

        }
	}
}
