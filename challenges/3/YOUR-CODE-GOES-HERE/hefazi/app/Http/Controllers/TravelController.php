<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Exceptions\ActiveTravelException;
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

	public function passengerOnBoard()
	{
	}

	public function done()
	{
	}

	public function take()
	{
	}
}
