<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Models\Travel;
use App\Http\Requests\TravelStoreRequest;

class TravelController extends Controller
{

	public function view()
	{
	}

	public function store(TravelStoreRequest $request)
	{
        $exist_active_travel = Travel::userHasActiveTravel(auth()->user());
        if($exist_active_travel) {
            return response()->json([
                'code' => 'ActiveTravel'
            ], 400);
        }

        $travel = new Travel;

        $travel->passenger_id = auth()->id();
        $travel->status = TravelStatus::SEARCHING_FOR_DRIVER->value;

        $travel->save();

        foreach ($request->spots as $item) {
            $travel->spots()->create([
                'position' => $item['position'],
                'latitude' => $item['latitude'],
                'longitude' => $item['longitude'],
            ]);
        }

        return response()->json([
            'travel' => [
                'spots' => $request->spots,
                'passenger_id' => auth()->id(),
                'status' => TravelStatus::SEARCHING_FOR_DRIVER->value
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
