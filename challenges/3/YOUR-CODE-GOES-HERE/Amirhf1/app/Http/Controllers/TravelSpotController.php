<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Exceptions\ProtectedSpotException;
use App\Exceptions\SpotAlreadyPassedException;
use App\Http\Requests\TravelSpotStoreRequest;
use App\Models\Travel;
use App\Models\TravelSpot;
use App\Services\TravelSpot\TravelSpotService;
use Illuminate\Http\JsonResponse;

class TravelSpotController extends Controller
{

    /**
     * @var TravelSpotService
     */
    protected TravelSpotService $travelSpotService;

    public function __construct(TravelSpotService $travelSpotService)
    {
        $this->travelSpotService = $travelSpotService;
    }

    /**
     * @param Travel $travel
     * @param TravelSpot $spot
     * @return JsonResponse
     * @throws InvalidTravelStatusForThisActionException
     * @throws SpotAlreadyPassedException
     */
    public function arrived(Travel $travel, TravelSpot $spot)
    {
        return $this->travelSpotService
            ->setTravelSpot($spot)
            ->setTravel($travel)
            ->checkTravelUserId('passenger_id')
            ->checkTravelStatus()
            ->isSpotAlreadyPassed()
            ->arrived();
    }

    /**
     * @param Travel $travel
     * @param TravelSpotStoreRequest $request
     * @return JsonResponse
     * @throws InvalidTravelStatusForThisActionException
     * @throws SpotAlreadyPassedException
     */
    public function store(Travel $travel, TravelSpotStoreRequest $request)
    {
        return $this->travelSpotService
            ->setTravel($travel)
            ->setSpotStoreRequest($request)
            ->checkTravelUserId('driver_id')
            ->checkTravelStatus()
            ->store();
    }

    /**
     * @param Travel $travel
     * @param TravelSpot $spot
     * @return JsonResponse
     * @throws InvalidTravelStatusForThisActionException
     * @throws SpotAlreadyPassedException
     * @throws ProtectedSpotException
     */
    public function destroy(Travel $travel, TravelSpot $spot)
    {
        return $this->travelSpotService
            ->setTravelSpot($spot)
            ->setTravel($travel)
            ->checkTravelUserId('driver_id')
            ->checkTravelStatus()
            ->isSpotAlreadyPassed()
            ->destroy();
    }
}
