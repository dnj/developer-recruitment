<?php

namespace App\Services\Driver;

use App\Enums\DriverStatus;
use App\Http\Resources\Driver\DriverResource;
use App\Models\Driver;
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

        return $this->CreateOrUpdateDriver($driver, $parameters);
    }

    /**
     * @param Driver $driver
     * @param array $parameters
     * @return JsonResponse|array
     */
    public function CreateOrUpdateDriver(Driver $driver, array $parameters): JsonResponse|array
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
            ],
            400);
    }

    public function checkNotExistDriver()
    {
        return !Driver::query()->where('id', auth()->user()->id)->exists();
    }


}
