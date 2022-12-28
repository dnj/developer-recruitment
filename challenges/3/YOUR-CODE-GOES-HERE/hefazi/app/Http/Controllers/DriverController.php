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
		$exist = Driver::where([
			'car_plate' => $request->car_plate,
			'car_model' => $request->car_model,
		])->exists();

		if ($exist) {
			return response()->json([
				'code' => 'AlreadyDriver'
			], 400);
		}
		$driver = Driver::create($request->all() + [
			'id' => auth()->id(),
			'status' => DriverStatus::NOT_WORKING->value
		]);

		return response()->json(DriverResource::make($driver));
	}

	public function update(Request $request)
	{
		$driver = Driver::byUser(auth()->user());
		$driver->update([
			'latitude' => $request->latitude,
			'longitude' => $request->longitude,
			'status' => $request->status
		]);

		$travels = Travel::with('spots')->where('status', TravelStatus::SEARCHING_FOR_DRIVER->value)->get();

		return response()->json([
			'driver'  => $request->all(),
			'travels' => $travels
		]);
	}
}
