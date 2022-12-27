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
use App\Http\Resources\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use Illuminate\Support\Facades\DB;

class TravelController extends Controller
{

	public function view(Travel $travel)
	{
		$this->authorize('view', $travel);
		return response()->json(['travel' => ['id' => $travel->id]]);
	}

	public function store(TravelStoreRequest $request)
	{
		$user = $request->user();
		$spots = $request->get('spots', []);

		$this->authorize('create', Travel::class);

		if (Travel::userHasActiveTravel($user)) {
			return throw new ActiveTravelException();
		}

		DB::beginTransaction();

		$travel = $user->travelByPassenger()->create(['status' => TravelStatus::SEARCHING_FOR_DRIVER]);
		$travel->spots()->createMany($spots);

		DB::commit();

		return TravelResource::make($travel);
	}

	public function cancel(Travel $travel)
	{
		$this->authorize('cancel', $travel);
		$isDriver = Driver::isDriver(auth()->user());

		if (!$isDriver && $travel->status == TravelStatus::RUNNING) {
			return throw new CannotCancelRunningTravelException();
		}

		foreach ($travel->events as $event) {
			if ($event->type == TravelEventType::PASSENGER_ONBOARD) {
				return throw new CannotCancelRunningTravelException();
			}
		}

		if (in_array($travel->status, [
			TravelStatus::DONE,
			TravelStatus::CANCELLED
		])) {
			return throw new CannotCancelFinishedTravelException();
		}

		if ($travel->isCancelAble($isDriver)) {
			$travel->status = TravelStatus::CANCELLED;
			$travel->save();
		}

		return TravelResource::make($travel);
	}

	public function passengerOnBoard(Travel $travel)
	{

		$this->authorize('markAsPassengerOnBoard', $travel);

		if ($travel->driverHasArrivedToOrigin() == false) {
			return throw new CarDoesNotArrivedAtOriginException();
		}

		if ($travel->passengerIsInCar()) {
			return throw new InvalidTravelStatusForThisActionException();
		}

		if ($travel->status == TravelStatus::DONE) {
			return throw new InvalidTravelStatusForThisActionException();
		}

		$travel->events()
			->create([
				'type' => TravelEventType::PASSENGER_ONBOARD,
			]);

		return TravelResource::make($travel);
	}

	public function done(Travel $travel)
	{
		$this->authorize('markAsDone', $travel);

		if ($travel->allSpotsPassed() == false) {
			return throw new AllSpotsDidNotPassException();
		}

		if ($travel->status != TravelStatus::DONE) {

			$travel->status = TravelStatus::DONE;
			$travel->save();

			$travel->events()->create(['type' => TravelEventType::DONE]);

			return TravelResource::make($travel);
		} else {
			return throw new InvalidTravelStatusForThisActionException();
		}
	}

	public function take(Travel $travel)
	{
		$this->authorize('take', $travel);
		$user = auth()->user();

		if (Travel::userHasActiveTravel($user)) {
			return throw new ActiveTravelException();
		}

		if ($travel->status != TravelStatus::SEARCHING_FOR_DRIVER) {
			return throw new InvalidTravelStatusForThisActionException();
		}

		$travel->driver_id = $user->id;
		$travel->save();

		return response()->json([
			'travel' => [
				'id' => $travel->id,
				'driver_id' => $user->id,
				'status' => $travel->status
			]
		]);
	}
}
