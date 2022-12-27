<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Exceptions\SpotAlreadyPassedException;
use App\Http\Requests\TravelSpotStoreRequest;
use App\Http\Requests\TravelStoreRequest;
use App\Http\Resources\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelSpot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TravelSpotController extends Controller
{
    public function arrived(Travel $travel, TravelSpot $travelSpot)
    {
        if (!auth()->user()->can('markAsArrived', $travelSpot)) abort(403);

        if (!Driver::isDriver(auth()->user())) abort(403);

        if ($travel->driverHasArrivedToOrigin()) throw new SpotAlreadyPassedException();

        if ($travel->status == TravelStatus::CANCELLED) throw new InvalidTravelStatusForThisActionException();

        DB::transaction(function () use ($travelSpot) {
            $travelSpot->update(['arrived_at' => Carbon::now()]);
        });

        return response()->json(TravelResource::make($travel));
    }

    public function store(Travel $travel, TravelSpotStoreRequest $request)
    {
        if (Driver::isDriver(auth()->user())) abort(403);

        if (!auth()->user()->can('create', $travel)) abort(403);

        if ($travel->allSpotsPassed()) throw new SpotAlreadyPassedException();

        $this->checkTravelStatus($travel);

        $travel->spots()->create($request->validated());

        return response()->json([
            'travel' => $travel->load('spots')
        ]);
    }

    private function checkTravelStatus(Travel $travel): void
    {
        if (in_array($travel->status, [TravelStatus::DONE->value, TravelStatus::CANCELLED->value])) throw new InvalidTravelStatusForThisActionException();
    }

    public function destroy(Travel $travel, TravelSpot $travelSpot)
    {
        if (Driver::isDriver(auth()->user())) abort(403);

        if (!auth()->user()->can('destroy', $travelSpot)) abort(403);

        $this->checkTravelStatus($travel);

        if (!empty($travelSpot->arrived_at)) throw new SpotAlreadyPassedException();

        $travelSpot->delete();

        return response()->json(TravelResource::make($travel));
    }
}
