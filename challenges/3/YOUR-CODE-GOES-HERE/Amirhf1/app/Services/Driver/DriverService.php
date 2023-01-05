<?php

namespace App\Services\Driver;

use App\Enums\DriverStatus;
use App\Enums\TravelStatus;
use App\Http\Resources\Driver\DriverResource;
use App\Models\Driver;
use App\Models\Travel;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class DriverService extends BaseService
{
    /**
     * @param array $parameters
     * @return array|JsonResponse
     */
    public function createDriver(array $parameters): JsonResponse|array
    {
        $driver = new Driver();

        return $this->createOrUpdateDriver($driver, $parameters);
    }

    /**
     * @param Driver $driver
     * @param array $parameters
     * @return JsonResponse|array
     */
    public function updateDriver($parameters): JsonResponse|array
    {
        $driver = Driver::byUser(auth()->user())->first();
        if (!$driver) {
            //if driver not found
            return response()->json([
                'code' => 'DriverNotFound'
            ], 400);
        }
        if ($parameters->has('latitude') and $parameters->has('longitude')) {
            $driver->latitude = $parameters->latitude;
            $driver->longitude = $parameters->longitude;
        }
        $driver->status = $parameters->status;
        if ($driver->save()) {
            $travels = Travel::with('spots')
                ->where('status', TravelStatus::SEARCHING_FOR_DRIVER->value)
                ->get();

            return response()->json([
                'driver' => $parameters->all(),
                'travels' => $travels
            ]);
        }
        return response()->json([
            'code' => 'DriverNotUpdate'
        ], 400);
    }

    /**
     * @param Driver $driver
     * @param array $parameters
     * @return JsonResponse|array
     */
    public function createOrUpdateDriver(Driver $driver, array $parameters): JsonResponse|array
    {
        if ($this->checkNotExistDriver()) {

            $driver->setId(auth()->user()->id);
            $driver->setCarPlate($parameters['car_plate']);
            $driver->setCarModel($parameters['car_model']);
            $driver->setStatus(DriverStatus::NOT_WORKING->value);
            $driver->save();

            return (new DriverResource($driver))
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        }

        return response()->json(
            [
                'code' => 'AlreadyDriver'
            ], 400);
    }

    public function checkNotExistDriver()
    {
        return !Driver::query()->where('id', auth()->user()->id)->exists();
    }

}
