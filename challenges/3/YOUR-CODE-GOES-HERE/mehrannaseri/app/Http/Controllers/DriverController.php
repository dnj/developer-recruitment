<?php

namespace App\Http\Controllers;

use App\Enums\DriverStatus;
use App\Http\Requests\DriverSignupRequest;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use Illuminate\Http\JsonResponse;

class DriverController extends Controller
{
	public function signup(DriverSignupRequest $request) :JsonResponse
	{
        $user = auth()->user();

        //checking user is driver
        if(Driver::isDriver($user))
            return response()->json([ "code" => "AlreadyDriver"], 400);

        $driver = $user->driver()->create([
            'car_model' => $request->car_model,
            'car_plate' => $request->car_plate,
            'status' => DriverStatus::NOT_WORKING->value
        ]);

        return response()->json([
            'driver' => DriverResource::make($driver)
        ]);
	}

	public function update()
	{
	}
}
