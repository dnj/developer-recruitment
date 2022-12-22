<?php

namespace App\Http\Controllers;

use App\Enums\TravelEventType;
use App\Enums\TravelStatus;
use App\Exceptions\ActiveTravelException;
use App\Exceptions\CarDoesNotArrivedAtOriginException;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Http\Requests\TravelStoreRequest;
use App\Models\Travel;
use Illuminate\Http\JsonResponse;

class TravelController extends Controller
{

	public function view()
	{
	}

	public function store(TravelStoreRequest $request): JsonResponse
	{
		if (Travel::userHasActiveTravel(auth()->user())) {
			throw new ActiveTravelException();
		}

		$travel = Travel::create([
			'passenger_id' => auth()->id(),
			'status' => TravelStatus::SEARCHING_FOR_DRIVER->value
		]);

		$apots = collect($request->spots)->map(function ($item) {
			return [
				'position' => $item['position'],
				'latitude' => $item['latitude'],
				'longitude' => $item['longitude'],
			];
		});

		$travel->spots()->createMany($apots->all());

		return response()->json([
			'travel' => [
				'spots'        => $request->spots,
				'passenger_id' => auth()->id(),
				'status'       => TravelStatus::SEARCHING_FOR_DRIVER->value
			]
		], 201);
	}

	public function cancel()
	{
	}

	public function passengerOnBoard(Travel $travel): JsonResponse
    {
        // $travel = $travel->with('events')->first();

        if ($travel->passenger_id == auth()->id()) {
            abort(403);
        }

        if (!$travel->driverHasArrivedToOrigin()) {
            throw new CarDoesNotArrivedAtOriginException();
        }

        $found = false;
        foreach ($travel->events as $e) {
            if ($e->type == TravelEventType::PASSENGER_ONBOARD) {
                $found = true;
                break;
            }
        }

        if ($found || ($travel->status == TravelStatus::DONE)) {
            throw new InvalidTravelStatusForThisActionException();
        }

        $travel->events()->create([
            'type' => TravelEventType::PASSENGER_ONBOARD->value
        ]);

        return response()->json([
            'travel' => $travel->with('events')->first()->toArray()
        ]);
    }

	public function done()
	{
	}

	public function take(Travel $travel): JsonResponse
	{
		if (Travel::userHasActiveTravel(auth()->user())) {
			throw new ActiveTravelException();
		}

		if ($travel->status == TravelStatus::CANCELLED) {
			throw new InvalidTravelStatusForThisActionException();
		}

		$travel->update([
			'driver_id' => auth()->id()
		]);

		$travel->events()->create([
			'type' => TravelEventType::ACCEPT_BY_DRIVER->value
		]);

		return response()->json([
			'travel' => collect($travel->toArray())->filter(function () {
				return [
					'id', 'driver_id', 'status'
				];
			})
		]);
	}
}
