<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use App\Http\Requests\DriverSignupRequest;
use App\Http\Requests\DriverUpdateRequest;
use function GuzzleHttp\Promise\all;
use App\Enums\TravelStatus;
use App\Enums\DriverStatus;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\User;

class DriverController extends Controller
{
	public function signup(DriverSignupRequest $request) : object
	{
        $check_driver = Driver::query()->where([
            'car_model' => $request->car_model,'car_plate' => $request->car_plate
        ]);

        if(!$check_driver->exists()) {
            $driver = Driver::query()->create([
                "id"        => User::factory()->create()->id,
                "car_plate" => $request->car_plate,
                "car_model" => $request->car_model,
                "status"    => DriverStatus::NOT_WORKING->value
            ]);

            return response()->json([
                'driver' => $driver
            ], HttpFoundationResponse::HTTP_OK);
        }else{
            return response()->json([
                "code" => "AlreadyDriver"
            ], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }
	}

	public function update(DriverUpdateRequest $request):object
	{
        if(Driver::isDriver(auth()->user())){

            $data = [
                "latitude"  => $request->latitude,
                "longitude" => $request->longitude,
                "status"    => DriverStatus::WORKING->value
            ];

            $driver = Driver::query()->where('id' , auth()->user()->id)->first();
            $driver->update($data);

            $travels = new Travel();
            $travels->driver()->associate($driver);
            $travels->passenger()->associate(auth()->user());
            $travels->status = TravelStatus::SEARCHING_FOR_DRIVER->value;
            $travels->save();

            return response()->json([
                'driver'  => $data,
                'travels' => Travel::query()->get()
            ] , HttpFoundationResponse::HTTP_OK);
        }
	}
}
