<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverSignupRequest;
use App\Services\Driver\DriverService;

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

    public function signup(DriverSignupRequest $request)
    {
        return $this->driverService->createDriver($request->toArray());
    }

	public function update()
	{
	}
}