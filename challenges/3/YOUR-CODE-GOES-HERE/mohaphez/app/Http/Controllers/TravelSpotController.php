<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Exceptions\ProtectedSpotException;
use App\Exceptions\SpotAlreadyPassedException;
use App\Http\Requests\TravelSpotStoreRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use App\Models\TravelSpot;

class TravelSpotController extends Controller
{
	public function arrived(Travel $travel, TravelSpot $spot)
	{
		$this->authorize('markAsArrived', $spot);

		if ($travel->driverHasArrivedToOrigin()) {
			return throw new SpotAlreadyPassedException();
		}

		if ($travel->status == TravelStatus::CANCELLED) {
			return throw new InvalidTravelStatusForThisActionException();
		}

		$orginSpot = $travel->getOriginSpot();
		$orginSpot->arrived_at = now();
		$orginSpot->save();

		return TravelResource::make($travel);
	}

	public function store(TravelSpotStoreRequest $request, Travel $travel)
	{
		$this->authorize('create', [TravelSpot::class, $travel]);

		if ($travel->allSpotsPassed()) {
			return throw new SpotAlreadyPassedException();
		}

		if ($travel->status == TravelStatus::CANCELLED) {
			return throw new InvalidTravelStatusForThisActionException();
		}

		$travel->spots()
			->where('position', '>=', $request->position)
			->increment('position');
		$travel->spots()->create($request->validated());

		return TravelResource::make($travel);
	}

	public function destroy(Travel $travel, TravelSpot $spot)
	{
		$this->authorize('destroy', $spot);

		if ($travel->allSpotsPassed()) {
			return throw  new SpotAlreadyPassedException();
		}

		if (!($travel->status == TravelStatus::RUNNING)) {
			return throw new InvalidTravelStatusForThisActionException();
		}

		if ($travel->isProtectedSpot($spot)) {
			return throw  new ProtectedSpotException();
		}

		TravelSpot::wherePosition($spot->id)->delete();

		return TravelResource::make($travel);
	}
}
