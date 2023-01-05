<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverSignupRequest;
use App\Http\Requests\DriverUpdateRequest;
use App\Services\Driver\DriverService;
use Illuminate\Http\JsonResponse;

class DriverController extends Controller
{
    /**
     * @var DriverService
     */
    protected DriverService $driverService;

    /**
     * @param DriverService $driverService
     */
    public function __construct(DriverService $driverService)
    {
        $this->driverService = $driverService;
    }

    /**
     * @param DriverSignupRequest $request
     * @return array|JsonResponse
     */
    public function signup(DriverSignupRequest $request)
    {
        return $this->driverService->createDriver($request->toArray());
    }

    /**
     * @param DriverUpdateRequest $request
     * @return array|JsonResponse
     */
    public function update(DriverUpdateRequest $request)
    {
        return $this->driverService->updateDriver($request);
    }
}
