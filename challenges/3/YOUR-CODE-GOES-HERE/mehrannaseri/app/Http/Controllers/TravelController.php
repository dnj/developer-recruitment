<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Http\Requests\TravelStoreRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;

class TravelController extends Controller
{

	public function view()
	{
	}

	public function store(TravelStoreRequest $request)
	{
        $passenger = auth()->user();

        $travel = Travel::create([
            'passenger_id' => $passenger->id,
            'status' => TravelStatus::SEARCHING_FOR_DRIVER->value
        ]);

        $travel->spots()->createMany($request->spots);

        return response()->json(TravelResource::make($travel), 201);

	}

	public function cancel($travel_id)
	{
        $passenger = auth()->user();

        $travel = Travel::searchingForDriver()
            ->where('passenger_id', $passenger->id)
            ->findOrFail($travel_id);

        $travel->update([
            'status' => TravelStatus::CANCELLED->value
        ]);

        return response()->json(TravelResource::make($travel));

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
