<?php

namespace App\Http\Controllers;

use App\Enums\DriverStatus;
use App\Http\Requests\DriverSignupRequest;
use App\Http\Requests\DriverUpdateRequest;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use App\Models\Travel;
use Illuminate\Support\Facades\Gate;

class DriverController extends Controller
{
	public function signup(DriverSignupRequest $req)
	{
        $user=auth('sanctum')->user();

       if(!Driver::isDriver($user)){

           $new_driver=new Driver();
           $new_driver->id=$user->id;
           $new_driver->car_plate=$req->car_plate;
           $new_driver->car_model=$req->car_model;
           $new_driver->status=DriverStatus::NOT_WORKING->value;
           $new_driver->save();

           return response()->json(DriverResource::make($new_driver));

       }else{

           return  response()->json([
               'code'=>"AlreadyDriver"
           ] ,400);

       }

	}

	public function update(DriverUpdateRequest $req)
	{

        $user= auth('sanctum')->user();

        $driver=Driver::query()->findOrFail($user->id);
        $driver->latitude=$req->latitude;
        $driver->longitude=$req->longitude;
        $driver->status=$req->status;
        $driver->save();

        $travels=Travel::query()->with('spots')->where('status','SEARCHING_FOR_DRIVER')->get();

        return response()->json([
            'driver'=>$req->all(),
            'travels'=>$travels
        ]);
	}
}
