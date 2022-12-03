<?php

namespace App\Http\Controllers;

use App\Enums\DriverStatus;
use App\Enums\TravelStatus;
use App\Http\Requests\DriverSignupRequest;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use App\Models\Travel;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function signup(DriverSignupRequest $request)
    {
        $driver_exist = Driver::query()->where([
            'car_plate' => $request->car_plate,
            'car_model' => $request->car_model,
        ])->exists();

        if ($driver_exist) {
            return response()->json([
                'code' => 'AlreadyDriver'
            ], 400);
        }

        $driver = new Driver;

        $driver->id = auth()->id();
        $driver->car_plate = $request->car_plate;
        $driver->car_model = $request->car_model;
        $driver->status = DriverStatus::NOT_WORKING->value;

        $driver->save();

        return response()->json(DriverResource::make($driver));
    }

    public function update(Request $request)
    {
        $driver = Driver::byUser(auth()->user())->first();

        $driver->latitude = $request->latitude;
        $driver->longitude = $request->longitude;
        $driver->status = $request->status;

        $driver->save();

        $travels = Travel::query()->with('spots')->where('status', TravelStatus::SEARCHING_FOR_DRIVER->value)->get();

        return response()->json([
            'driver'  => $request->all(),
            'travels' => $travels
        ]);
    }
}
