<?php

namespace App\Http\Controllers;

use App\Enums\DriverStatus;
use App\Enums\TravelStatus;
use App\Exceptions\AlreadyDriverException;
use App\Http\Requests\DriverSignupRequest;
use App\Http\Resources\DriverResource;
use App\Http\Resources\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use Illuminate\Http\Request;

class DriverController extends Controller {
	/**
	 * @param \App\Http\Requests\DriverSignupRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \App\Exceptions\AlreadyDriverException
	 */
	public function signup ( DriverSignupRequest $request ) {
		
		$user = $request->user();
		if ( $user->can('signup' , new Driver()) ) {
			if ( Driver::isDriver($user) ) {
				throw new AlreadyDriverException();
			}
			$driver = Driver::query()
							->create([
										 'id' => $user->id ,
										 'car_plate' => $request->get('car_plate') ,
										 'car_model' => $request->get('car_model') ,
										 'status' => DriverStatus::NOT_WORKING->value ,
									 ]);
			
			return response()->json([
										'driver' => DriverResource::make($driver->load('user')) ,
									]);
		}
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse|void
	 */
	public function update ( Request $request ) {
		$user = $request->user();
		$driver = Driver::byUser($user);
		if ( $user->can('update' , $driver) ) {
			$driver->latitude = $request->get('latitude');
			$driver->longitude = $request->get('longitude');
			$driver->status = $request->get('status');
			$driver->save();
			$travels = Travel::query()
							 ->where('status' , TravelStatus::SEARCHING_FOR_DRIVER->value)
							 ->get();
			
			return response()->json([
										'driver' => DriverResource::make($driver->load('user')) ,
										'travels' => $travels->load([
																		"spots" ,
																		"events" ,
																	]) ,
									]);
		}
	}
}