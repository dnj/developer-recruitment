<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Exceptions\SpotAlreadyPassedException;
use App\Models\Travel;
use App\Models\TravelSpot;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class TravelSpotController extends Controller
{
	public function arrived(Travel $travel, TravelSpot $spot): JsonResponse
	{
		$travel = $travel->with('spots')->first();

		if ($travel->passenger_id == auth()->id()) {
			abort(403);
		}

		if ($travel->status == TravelStatus::CANCELLED) {
			throw new InvalidTravelStatusForThisActionException();
		}

		if ($travel->spots()->where('position', $spot->position)->whereNotNull('arrived_at')->exists()) {
			throw new SpotAlreadyPassedException();
		}

		$travel->spots()->where('position', $spot->position)->update([
			'arrived_at' => Carbon::now()
		]);

		return response()->json([
			'travel' => $travel->with('spots')->first()->toArray()
		]);
	}

	public function store()
	{
	}

	public function destroy()
	{
	}
}
