<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Exceptions\AlreadyDriverException;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Exceptions\ProtectedSpotException;
use App\Exceptions\SpotAlreadyPassedException;
use App\Http\Requests\TravelSpotStoreRequest;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelSpot;
use Illuminate\Support\Facades\Date;

class TravelSpotController extends Controller
{
    /**
     * @throws InvalidTravelStatusForThisActionException
     * @throws SpotAlreadyPassedException
     */
    public function arrived(Travel $travel): \Illuminate\Http\JsonResponse
    {

        $user=auth('sanctum')->user();

        if(!Driver::isDriver($user) ) {

            return response()->json([],403);

        }

        if(!($travel->status==TravelStatus::RUNNING)){

            throw  new InvalidTravelStatusForThisActionException();

        }

        if($travel->driverHasArrivedToOrigin()){

            throw  new SpotAlreadyPassedException();

        }

        $origin_Spot=$travel->getOriginSpot();
        $origin_Spot->arrived_at=Date::now();
        $origin_Spot->save();

        return response()->json([
            'travel'=>[
                'spots'=>array($origin_Spot)
            ]
        ]);

	}

    /**
     * @throws SpotAlreadyPassedException
     * @throws InvalidTravelStatusForThisActionException
     */
    public function store($travel,TravelSpotStoreRequest $req): \Illuminate\Http\JsonResponse
    {

        $travel=Travel::query()->where('id',$travel)->with('spots')->first();

        $user=auth('sanctum')->user();

        if(Driver::isDriver($user) ) {

            return response()->json([],403);

        }

        if($travel->allSpotsPassed()){

            throw  new SpotAlreadyPassedException();

        }

        if(!($travel->status==TravelStatus::RUNNING)){

            throw  new InvalidTravelStatusForThisActionException();

        }

        foreach ($travel->spots as $spot){

            if($spot->position>=$req->position){

                $travel->spots()->where('position',$req->position)->increment('position');
                break;
            }

        }

        $travel->spots()->create($req->toArray());

        $travel_with_spots=Travel::query()->where('id',$travel->id)->with('spots')->first();

        return response()->json([
            'travel'=>[
                'spots'=>$travel_with_spots->spots
            ]
        ]);

	}

    /**
     * @throws InvalidTravelStatusForThisActionException
     * @throws SpotAlreadyPassedException
     * @throws ProtectedSpotException
     */
    public function destroy(Travel $travel, $spot)
	{

        $user=auth('sanctum')->user();

        if(Driver::isDriver($user) ) {

            return response()->json([],403);

        }

        if(!($travel->status==TravelStatus::RUNNING)){

            throw  new InvalidTravelStatusForThisActionException();

        }

        if($travel->allSpotsPassed()){

            throw  new SpotAlreadyPassedException();

        }

        $origin_Spot=$travel->getOriginSpot();
        if($origin_Spot->id==$spot){ throw  new ProtectedSpotException(); }

        $travel=$travel->with('spots')->first();
        $spot_to_be_deleted=TravelSpot::query()->findOrFail($spot);

         if( (count($travel->spots)-1)== $spot_to_be_deleted->position){

             throw  new ProtectedSpotException();

         }

        $travel->spots()->where('position',$spot)->delete();
        $travel= $travel->with('spots')->first();

        return response()->json([

            'travel'=>$travel

        ]);

	}
}
