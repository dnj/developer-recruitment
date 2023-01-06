<?php

namespace App\Http\Controllers;

use App\Enums\DriverStatus;
use App\Exceptions\AlreadyDriverException;
use App\Http\Requests\DriverSignupRequest;
use App\Http\Requests\DriverUpdateRequest;
use App\Http\Resources\DriverResource;
use App\Http\Resources\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class DriverController extends Controller
{
    private /**
     * @var User|Authenticatable|null
     */ $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function signup(DriverSignupRequest $request) :JsonResponse
	{
        //checking user is driver
        if(Driver::isDriver($this->user))
            throw new AlreadyDriverException();

        $driver = $this->user->driver()->create([
            'car_model' => $request->car_model,
            'car_plate' => $request->car_plate,
            'status' => DriverStatus::NOT_WORKING->value
        ]);

        return response()->json([
            'driver' => DriverResource::make($driver)
        ]);
	}

	public function update(DriverUpdateRequest $request)
	{
        $driver = $this->user->driver;

        $driver->update([
            'status' => $request->status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        $travels = Travel::with('spots')->searchingForDriver()->get();

        return response()->json([
            'driver' => DriverResource::make($driver),
            'travels' => $travels
        ]);
	}
}
