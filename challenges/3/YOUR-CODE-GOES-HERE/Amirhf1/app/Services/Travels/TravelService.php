<?php

namespace App\Services\Travels;

use App\Enums\TravelEventType;
use App\Enums\TravelStatus;
use App\Exceptions\ActiveTravelException;
use App\Exceptions\AllSpotsDidNotPassException;
use App\Exceptions\CannotCancelFinishedTravelException;
use App\Exceptions\CannotCancelRunningTravelException;
use App\Exceptions\CarDoesNotArrivedAtOriginException;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Http\Resources\Travel\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelEvent;
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

    public function passengerOnBoard(Travel $travel)
    {

        $this->checkTravel($travel);

        if (!$travel->driverHasArrivedToOrigin()) {
            throw new CarDoesNotArrivedAtOriginException();
        }

        $eventExist = $travel->events()
            ->where('type', '=', TravelEventType::PASSENGER_ONBOARD)
            ->exists();

        if ($eventExist) {
            throw new InvalidTravelStatusForThisActionException();
        }

        $this->createTravelEvent($travel->id, TravelEventType::PASSENGER_ONBOARD->value);

        return response()->json([
            'travel' => $travel->with('events')->first()->toArray()
        ]);
    }

    public function done(Travel $travel)
    {
        $travel = $travel->with('events')->first();

        $this->checkTravel($travel);

        if (!$travel->allSpotsPassed() &&! $travel->passengerIsInCar()) {
            throw new AllSpotsDidNotPassException();
        }

        $travel->setStatusDone();
        $this->createTravelEvent($travel->id, TravelEventType::DONE->value);

        return response()->json(
            [
                'travel' => $travel->with('events')->first()->toArray()
            ]
        );
    }

    /**
     * @param Travel $travel
     * @return void
     * @throws InvalidTravelStatusForThisActionException
     */
    private function checkTravel(Travel $travel): void
    {
        if ($travel->passenger_id == auth()->id()) {
            abort(ResponseAlias::HTTP_FORBIDDEN);
        }
        $this->checkTravelStatus($travel, TravelStatus::DONE);
    }

    /**
     * @param int $travelId
     *
     * @return TravelEvent
     */
    private function createTravelEvent(int $travelId, string $type): TravelEvent
    {
        $travelEvent = new TravelEvent();
        $travelEvent->travel_id = $travelId;
        $travelEvent->type = $type;
        $travelEvent->save();

        return $travelEvent->refresh();
    }

    /**
     * @param Travel $travel
     * @return JsonResponse
     * @throws ActiveTravelException
     * @throws InvalidTravelStatusForThisActionException
     */
    public function take(Travel $travel): JsonResponse
    {
        if (Travel::userHasActiveTravel(auth()->user())) {
            throw new ActiveTravelException();
        }
        $this->checkTravelStatus($travel, TravelStatus::CANCELLED);
        $travel->assignDriverId(auth()->id());
        $this->createTravelEvent($travel->id, TravelEventType::ACCEPT_BY_DRIVER->value);

        return response()->json([
            'travel' => collect($travel->toArray())->filter(function () {
                return [
                    'id', 'driver_id', 'status'
                ];
            })
        ]);
    }

    /**
     * @param Travel $travel
     * @return void
     * @throws InvalidTravelStatusForThisActionException
     */
    public function checkTravelStatus(Travel $travel, $status): void
    {
        if ($travel->status == $status) {
            throw new InvalidTravelStatusForThisActionException();
        }
    }
}

