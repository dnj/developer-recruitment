<?php

namespace App\Services\Travels;

use App\Enums\TravelStatus;
use App\Exceptions\ActiveTravelException;
use App\Exceptions\CannotCancelFinishedTravelException;
use App\Exceptions\CannotCancelRunningTravelException;
use App\Http\Resources\Travel\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelSpot;
use Faker\Provider\Base;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TravelService extends Base
{
    /**
     * @param Travel $travel
     * @return JsonResponse
     */
    public function view(Travel $travel): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            ['travel' => ['id' => $travel->id]]
        );
    }

    public function store($parameter)
    {
        if (Travel::userHasActiveTravel(auth()->user()))
            throw new ActiveTravelException();

        $travel = new Travel();
        $travel->setPassengerId(auth()->id());
        $travel->setStatus(TravelStatus::SEARCHING_FOR_DRIVER->value);
        $travel->save();

        $travelSpot = new TravelSpot();
        foreach ($parameter->spots as $spot) {
            $travelSpot->setTravelId($travel->id);
            $travelSpot->setPosition($spot['position']);
            $travelSpot->setLatitude($spot['latitude']);
            $travelSpot->setLongitude($spot['longitude']);
            $travelSpot->save();
        }

        return response()->json([
            'travel' => [
                'spots' => $parameter->spots,
                'passenger_id' => auth()->id(),
                'status' => TravelStatus::SEARCHING_FOR_DRIVER->value
            ]
        ], ResponseAlias::HTTP_CREATED);
    }

    /**
     * @param $travel
     * @return JsonResponse
     * @throws CannotCancelFinishedTravelException
     * @throws CannotCancelRunningTravelException
     */
    public function cancel($travel): JsonResponse
    {
        $travel = Travel::query()->findOrFail($travel->getId());

        $this->CanNotCancelTravel($travel);

        $travel->update(
            [
                'status' => TravelStatus::CANCELLED->value
            ]
        );

        return response()->json(TravelResource::make($travel));
    }

    /**
     * @param $travel
     * @return void
     * @throws CannotCancelFinishedTravelException
     * @throws CannotCancelRunningTravelException
     */
    private function CanNotCancelTravel($travel)
    {
        $user = auth()->user();

        if ($travel->passengerIsInCar())
            throw new CannotCancelRunningTravelException();

        if ($travel->driverHasArrivedToOrigin() and !Driver::isDriver($user))
            throw new CannotCancelRunningTravelException();

        if ($travel->status->value == TravelStatus::DONE->value
            or $travel->status->value == TravelStatus::CANCELLED->value)
            throw new CannotCancelFinishedTravelException();

    }
}

