<?php

namespace App\Http\Controllers;

use App\Exceptions\ActiveTravelException;
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
     * @param TravelService $travelService
     */
    public function __construct(TravelService $travelService)
    {
        $this->travelService = $travelService;
    }

    /**
     * @param Travel $travel
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

    public function cancel(Travel $travel)
    {
        return $this->travelService->cancel($travel);
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
