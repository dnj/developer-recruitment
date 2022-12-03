<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Models\Travel;
use App\Http\Requests\TravelStoreRequest;
use Illuminate\Http\Request;

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

	public function cancel(int $travel)
	{
        $travel = Travel::query()->where('id', $travel)->first();

        if($travel) {
            if(in_array($travel->status, [
                TravelStatus::CANCELLED,
                TravelStatus::DONE
            ])) {
                return response()->json([
                    'code' => 'CannotCancelFinishedTravel'
                ], 400);
            } else {
                $travel->status = TravelStatus::CANCELLED->value;
                $travel->save();

                return response()->json([
                    'travel' => $travel
                ]);
            }
        } else {
            return response()->json([
                'code' => 'TravelNotFound'
            ], 400);
        }
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
