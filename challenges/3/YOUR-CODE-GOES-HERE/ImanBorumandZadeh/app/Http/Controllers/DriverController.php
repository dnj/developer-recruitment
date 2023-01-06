<?php

namespace App\Http\Controllers;



use App\Exceptions\AlreadyDriverException;
use App\Http\Requests\Driver\DriverSignupRequest;
use App\Http\Requests\Driver\DriverUpdateRequest;
use App\Http\Resources\Driver\DriverResource;
use App\Http\Resources\Travel\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use Illuminate\Http\JsonResponse;

class DriverController extends Controller
{


    /**
     * @param DriverSignupRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function signup(DriverSignupRequest $request) : JsonResponse
    {
        $user = auth()->user();

        //if user was driver return an exception
        throw_if(Driver::isDriver($user), new AlreadyDriverException());

        return $this->apiResponse([
            'driver' => new DriverResource(
                $user->driver()->create($request->validated())
            )
        ]);
	}


    /**
     * @param DriverUpdateRequest $request
     * @return JsonResponse
     */
    public function update( DriverUpdateRequest $request) : JsonResponse
    {
        $driver = Driver::byUser($request->user());
        $driver->update($request->validated());

        return $this->apiResponse([
            'driver' => new DriverResource($driver),
            'travels' => TravelResource::collection(
                Travel::SearchingForDriver()
                      ->with(['spots', 'events'])
                      ->get()
            )
        ]);
	}
}
