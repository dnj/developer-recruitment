<?php

namespace App\Http\Controllers;

use App\Enums\TravelEventType;
use App\Enums\TravelStatus;
use App\Exceptions\ActiveTravelException;
use App\Exceptions\AllSpotsDidNotPassException;
use App\Exceptions\CannotCancelFinishedTravelException;
use App\Exceptions\CannotCancelRunningTravelException;
use App\Exceptions\CarDoesNotArrivedAtOriginException;
use App\Exceptions\DriverCannotTravelException;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Http\Requests\TravelStoreRequest;
use App\Http\Resources\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TravelController extends Controller {
	public function view ( Travel $travel , Request $request ) {
		
		if ( !auth('sanctum')->check() ) {
			abort(401);
		}
		$user = $request->user();
		if ( $user->can('view' , $travel) ) {
			if ( $travel->status->value == TravelStatus::RUNNING->value ) {
				return response()->json(TravelResource::make($travel));
			}
		}
	}
	
	public function store ( TravelStoreRequest $request ) {
		
		if ( !auth('sanctum')->check() ) {
			abort(401);
		}
		$user = $request->user();
		if ( Travel::userHasActiveTravel($user) ) {
			throw new ActiveTravelException();
		}
		$spots = $request->get('spots' , []);
		$travel = new Travel();
		if ( $user->can('create' , $travel) ) {
			DB::beginTransaction();
			$travel = Travel::query()
							->create([
										 'passenger_id' => $user->id ,
										 'status' => TravelStatus::SEARCHING_FOR_DRIVER->value ,
									 ]);
			$travel->spots()
				   ->createMany($spots);
			DB::commit();
			
			return response()->json(TravelResource::make($travel) , 201);
		}
	}
	
	public function cancel ( Travel $travel , Request $request ) {
		$user = $request->user();
		if ( $user->can('cancel' , $travel) ) {
			
			foreach ( $travel->events as $event ) {
				if ( $event->type->value == TravelEventType::PASSENGER_ONBOARD->value ) {
					throw new CannotCancelRunningTravelException();
				}
			}
			if ( in_array($travel->status->value , [
				TravelStatus::DONE->value ,
				TravelStatus::CANCELLED->value ,
			]) ) {
				throw new CannotCancelFinishedTravelException();
			}
			if ( $travel->status->value === TravelStatus::SEARCHING_FOR_DRIVER->value ) {
				$travel->status = TravelStatus::CANCELLED->value;
				$travel->save();
				
				return response()->json(TravelResource::make($travel));
			}
			if ( Driver::isDriver($user) && $travel->status->value === TravelStatus::RUNNING->value ) {
				$travel->status = TravelStatus::CANCELLED->value;
				$travel->save();
				
				return response()->json(TravelResource::make($travel));
			}
			if ( !Driver::isDriver($user) && $travel->status->value === TravelStatus::RUNNING->value ) {
				throw new CannotCancelRunningTravelException();
			}
		}
	}
	
	public function passengerOnBoard ( Travel $travel , Request $request ) {
		
		$user = $request->user();
		if ( !Driver::isDriver($user) ) {
			abort(403);
		}
		if ( $user->can('markAsPassengerOnBoard' , $travel) ) {
			if ( $travel->driverHasArrivedToOrigin() == false ) {
				throw new CarDoesNotArrivedAtOriginException();
			}
			if ( $travel->status->value == TravelStatus::DONE->value ) {
				throw new InvalidTravelStatusForThisActionException();
			}
			if ( $travel->passengerIsInCar() ) {
				return throw new InvalidTravelStatusForThisActionException();
			}
			$travel->events()
				   ->create([
								'type' => TravelEventType::PASSENGER_ONBOARD ,
							]);
			
			return response()->json(TravelResource::make($travel));
		}
	}
	
	public function done ( Travel $travel , Request $request ) {
		$user = $request->user();
		if ( !Driver::isDriver($user) ) {
			abort(403);
		}
		if ( $user->can('markAsDone' , $travel) ) {
			if ( $travel->allSpotsPassed() == false ) {
				return throw new AllSpotsDidNotPassException();
			}
			if ( $travel->status->value != TravelStatus::DONE->value ) {
				$travel->status = TravelStatus::DONE->value;
				$travel->save();
				$travel->events()
					   ->create([
									'type' => TravelEventType::DONE->value ,
								]);
				
				return response()->json(TravelResource::make($travel));
			}
			else {
				throw new InvalidTravelStatusForThisActionException();
			}
		}
	}
	
	public function take ( Travel $travel , Request $request ) {
		$user = $request->user();
		if ( $user->can('take' , $travel) ) {
			$driver = Driver::byUser($user);
			if ( Travel::userHasActiveTravel($user) ) {
				throw new ActiveTravelException();
			}
			if ( $travel->status->value != TravelStatus::SEARCHING_FOR_DRIVER->value ) {
				throw new InvalidTravelStatusForThisActionException();
			}
			$travel->driver_id = $driver->id;
			$travel->save();
			
			return [
				'travel' => [
					'id' => $travel->id ,
					'driver_id' => $driver->id ,
					'status' => $travel->status->value ,
				] ,
			];
		}
	}
}
