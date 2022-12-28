<?php

namespace App\Http\Controllers;

use App\Enums\DriverStatus;
use App\Enums\TravelStatus;
use App\Exceptions\AlreadyDriverException;
use App\Http\Requests\DriverSignupRequest;
use App\Http\Requests\DriverUpdateRequest;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use App\Models\Travel;

class DriverController extends Controller
{
	public function signup(DriverSignupRequest $request)
	{
		$user = $request->user();

		$this->authorize('signup', Driver::class);

		if (Driver::isDriver($user)) {
			return throw new AlreadyDriverException();
		}

		$driver = $user->driver()
			->create($request->merge(['status' => DriverStatus::NOT_WORKING])->all());

		return DriverResource::make($driver)->response()->setStatusCode(200);
	}

	public function update(DriverUpdateRequest $request)
	{
		$driver = Driver::byUser($request->user());

		$this->authorize('update', $driver);

		$driver->update($request->validated());

		$travels = Travel::whereStatus(TravelStatus::SEARCHING_FOR_DRIVER)->get();

		return DriverResource::make($driver->load('user'))
			->additional(
				['travels' => $travels->load(["spots", "events"])]
			);
	}
}
