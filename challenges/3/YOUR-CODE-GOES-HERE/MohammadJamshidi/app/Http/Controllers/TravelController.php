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
use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelEvent;
use http\Env\Response;

class TravelController extends Controller
{

	public function view(Travel $travel): \Illuminate\Http\JsonResponse
    {

        return response()->json([
            'travel'=>[
                'id'=>$travel->id
            ]
        ]);

	}

    /**
     * @throws ActiveTravelException
     */
    public function store(TravelStoreRequest $req): \Illuminate\Http\JsonResponse
    {

        $passenger= auth('sanctum')->user();

       if(!Travel::userHasActiveTravel($passenger)) {

           $new_travel = new Travel();
           $new_travel->passenger_id = $passenger->id;
           $new_travel->status = TravelStatus::SEARCHING_FOR_DRIVER->value;
           $new_travel->save();

           foreach ($req->spots as $spot) {

               $new_travel->spots()->create($spot);

           }

           return response()->json([

               'travel' => [
                   'spots' => $req->spots,
                   'passenger_id' => $new_travel->passenger_id,
                   'status' => $new_travel->status
               ]

           ], 201);

       }else{

           throw new ActiveTravelException();

       }
	}

    /**
     * @throws CannotCancelFinishedTravelException
     * @throws CannotCancelRunningTravelException
     */
    public function cancel($id): \Illuminate\Http\JsonResponse
    {
        $user=auth('sanctum')->user();

        $travel=Travel::query()->findOrFail($id);

        if( $travel->status==TravelStatus::CANCELLED or $travel->status==TravelStatus::DONE){

            throw  new CannotCancelFinishedTravelException();

        }else if ($travel->status==TravelStatus::RUNNING and $travel->passengerIsInCar()){

            throw  new CannotCancelRunningTravelException();

        }else if ( $travel->status==TravelStatus::RUNNING and $travel->driverHasArrivedToOrigin() and !Driver::isDriver($user)){

            throw  new CannotCancelRunningTravelException();

        }else{

            $travel->status=TravelStatus::CANCELLED->value;
            $travel->save();

            return response()->json([
                'travel'=>$travel
            ]);
        }



	}


    /**
     * @throws InvalidTravelStatusForThisActionException
     * @throws CarDoesNotArrivedAtOriginException
     */
    public function passengerOnBoard(Travel $travel): \Illuminate\Http\JsonResponse
    {

       $travel= $travel->with('events')->first();

        $user=auth('sanctum')->user();

        if(!Driver::isDriver($user) ) {

            return response()->json([],403);

        }

        if(!$travel->driverHasArrivedToOrigin()){

            throw  new CarDoesNotArrivedAtOriginException();

        }

        if($travel->passengerIsInCar()) {

            throw  new InvalidTravelStatusForThisActionException();

        }

        if( $travel->events()->pluck('type')->contains(TravelEventType::DONE) || $travel->status==TravelStatus::DONE ){

            throw new InvalidTravelStatusForThisActionException();
        }

        $travel->events()->create([

            'type'=>TravelEventType::PASSENGER_ONBOARD->value
        ]);

        $travel_events=TravelEvent::query()->where('travel_id',$travel->id)->get();

        return  response()->json([

            'travel'=>[

                'events'=>$travel_events
            ]

        ]);
	}

    /**
     * @throws AllSpotsDidNotPassException
     * @throws CarDoesNotArrivedAtOriginException
     */
    public function done(Travel $travel): \Illuminate\Http\JsonResponse
    {

        $travel= $travel->with('events')->first();

        $user=auth('sanctum')->user();

        if(!Driver::isDriver($user) ) {

            return response()->json([],403);

        }

        if( $travel->events()->pluck('type')->contains(TravelEventType::DONE)){

            throw new InvalidTravelStatusForThisActionException();
        }

        if(!$travel->driverHasArrivedToOrigin()){

            throw  new CarDoesNotArrivedAtOriginException();

        }

        if(!$travel->allSpotsPassed()){

            throw  new AllSpotsDidNotPassException();

        }

        $travel->events()->create([

            'type'=>TravelEventType::DONE->value
        ]);

        $travel_events=TravelEvent::query()->where('travel_id',$travel->id)->get();

        return  response()->json([

            'travel'=>[
                'status'=>$travel->status=TravelStatus::DONE,
                'events'=>$travel_events
            ]

        ]);

	}

    /**
     * @throws InvalidTravelStatusForThisActionException
     * @throws ActiveTravelException
     */
    public function take(Travel $travel): \Illuminate\Http\JsonResponse
    {

        $user=auth('sanctum')->user();

        if(!Driver::isDriver($user) ) {

            return response()->json([],403);

        }

        if($travel->status==TravelStatus::CANCELLED){

            throw  new InvalidTravelStatusForThisActionException();

        }

        $all_travels=Travel::query()->where('driver_id',$user->id)->get();
        $has_active_travel=false;
        foreach ( $all_travels as $item){

            if($item->status==TravelStatus::RUNNING){

                $has_active_travel=true;
                break;

            }

        }

        if($has_active_travel){  throw  new ActiveTravelException(); }

        $travel->driver_id=$user->id;
        $travel->save();

        return  response()->json([

            'travel'=>$travel

        ]);

	}
}
