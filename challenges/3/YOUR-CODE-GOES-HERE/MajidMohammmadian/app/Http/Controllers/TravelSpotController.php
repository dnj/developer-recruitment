<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Models\Travel;
use Carbon\Carbon;

class TravelSpotController extends Controller
{
	public function arrived(int $travel_id, int $spot_id)
	{
        $query = Travel::query()->with('spots')->where('id', $travel_id);

        $travel = $query->first();

        if($travel instanceof Travel) {
            if($travel->passenger_id == auth()->id()) {
                abort(403);
            }

            if($travel->status == TravelStatus::CANCELLED) {
                return response()->json([
                    'code' => 'InvalidTravelStatusForThisAction'
                ], 400);
            }

            $travel_arrived_exist = $travel->spots()->where('position', 0)->whereNotNull('arrived_at')->exists();

            if($travel->passenger_id == auth()->id() && $travel_arrived_exist) {
                abort(403);
            }

            if($travel_arrived_exist) {
                return response()->json([
                    'code' => 'SpotAlreadyPassed'
                ], 400);
            }

            $travel->spots()->where('position', 0)->update([
                'arrived_at' => Carbon::now()
            ]);

            $travel = $query->first();

            return response()->json([
                'travel' => $travel->toArray()
            ]);
        } else {
            return response()->json([
                'code' => 'TravelNotFound'
            ], 400);
        }
	}

	public function store()
	{
	}

	public function destroy()
	{
	}
}
