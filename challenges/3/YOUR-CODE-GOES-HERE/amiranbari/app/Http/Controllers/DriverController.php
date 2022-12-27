<?php

namespace App\Http\Controllers;

use App\Enums\DriverStatus;
use App\Enums\TravelStatus;
use App\Exceptions\AlreadyDriverException;
use App\Http\Requests\DriverSignupRequest;
use App\Http\Requests\DriverUpdateRequest;
use App\Http\Resources\DriverResource;
use App\Http\Resources\TravelResource;
use App\Models\Driver;
use App\Models\Travel;

class DriverController extends Controller
{
    public function signup(DriverSignupRequest $request)
    {
        if (Driver::isDriver(auth()->user())) throw new AlreadyDriverException();

        $driver = Driver::query()
            ->create($request->validated() + [
                    'id' => auth()->id(),
                    'status' => DriverStatus::NOT_WORKING->value
                ]);

        return response()->json([
            'driver' => DriverResource::make($driver)
        ]);
    }

    public function update(DriverUpdateRequest $request)
    {
        $driver = Driver::byUser(auth()->user());

        $driver->update($request->validated());

        $travels = Travel::with('spots', 'events')->where('status', TravelStatus::SEARCHING_FOR_DRIVER->value)->get();

        return response()->json([
            'driver'  => DriverResource::make($driver),
            'travels' => $travels
        ]);
    }
}
