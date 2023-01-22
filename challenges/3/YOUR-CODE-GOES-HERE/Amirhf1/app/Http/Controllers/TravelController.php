<?php

namespace App\Http\Controllers;

use App\Exceptions\ActiveTravelException;
use App\Exceptions\CannotCancelFinishedTravelException;
use App\Exceptions\CannotCancelRunningTravelException;
use App\Http\Requests\TravelStoreRequest;
use App\Models\Travel;
use App\Services\Travels\TravelService;
use Illuminate\Http\JsonResponse;

class TravelController extends Controller
{
    /**
     * @var TravelService
     */
    protected TravelService $travelService;

    /**
     * @param  TravelService  $travelService
     */
    public function __construct(TravelService $travelService)
    {
        $this->travelService = $travelService;
    }

    /**
     * @param  Travel  $travel
     * @return JsonResponse
     */
    public function view(Travel $travel)
    {
        return $this->travelService->view($travel);
    }

    /**
     * @throws ActiveTravelException
     */
    public function store(TravelStoreRequest $request)
    {
        return $this->travelService->store($request);
    }

    /**
     * @param  Travel  $travel
     * @return JsonResponse
     *
     * @throws CannotCancelFinishedTravelException
     * @throws CannotCancelRunningTravelException
     */
    public function cancel(Travel $travel)
    {
        return $this->travelService->cancel($travel);
    }

    public function passengerOnBoard(Travel $travel)
    {
        return $this->travelService->passengerOnBoard($travel);
    }

    public function done(Travel $travel)
    {
        return $this->travelService->done($travel);
    }

    public function take(Travel $travel)
    {
        return $this->travelService->take($travel);
    }
}
