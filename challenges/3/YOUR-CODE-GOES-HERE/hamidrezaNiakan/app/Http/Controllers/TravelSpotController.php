<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Exceptions\ProtectedSpotException;
use App\Exceptions\SpotAlreadyPassedException;
use App\Http\Resources\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelSpot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TravelSpotController extends Controller {
	public function arrived ( Travel $travel , TravelSpot $spot , Request $request ) {
		DB::beginTransaction();
		$user = $request->user();
		if ( !Driver::isDriver($user) ) {
			abort(403);
		}
		if ( $user->can('markAsArrived' , $spot) ) {
			if ( $travel->driverHasArrivedToOrigin() ) {
				return throw new SpotAlreadyPassedException();
			}
			if ( $travel->status->value == TravelStatus::CANCELLED->value ) {
				throw new InvalidTravelStatusForThisActionException();
			}
			$spot = $travel->getOriginSpot();
			$spot->arrived_at = Carbon::now();
			$spot->save();
			$travel->status = TravelStatus::RUNNING;
			$travel->save();
			
			return response()->json(TravelResource::make($travel));
		}
		DB::commit();
	}
	
	public function store ( Travel $travel , Request $request ) {
		$this->validate($request , [
			'position' => [
				'required' ,
				'integer' ,
				'min:0' ,
				'between:0,1' ,
			] ,
			'latitude' => [
				'required' ,
				'min:-90' ,
				'max:90' ,
			] ,
			'longitude' => [
				'required' ,
				'min:-180' ,
				'max:180' ,
			] ,
		]);
		$user = $request->user();
		if ( Driver::isDriver($user) ) {
			abort(403);
		}
		if ( $user->can('create' , $travel) ) {
			if ($travel->allSpotsPassed()) {
				throw new SpotAlreadyPassedException();
			}
			if ($travel->status->value == TravelStatus::CANCELLED->value) {
				throw new InvalidTravelStatusForThisActionException();
			}
			$travel_spot = TravelSpot::query()
									 ->create([
												  'travel_id' => $travel->id ,
												  'latitude' => $request->get('latitude') ,
												  'longitude' => $request->get('longitude') ,
												  'position' => $request->get('position') ,
											  ]);
			
			return response()->json([
										'travel' => [
											'spots' => [
												[
													'latitude' => $travel_spot->latitude ,
													'longitude' => $travel_spot->longitude ,
													'position' => $travel_spot->position ,
												] ,
											] ,
										] ,
									]);
		}
	}
	
	public function destroy ( Travel $travel , TravelSpot $spot , Request $request) {
		$user = $request->user();
		if ( Driver::isDriver($user) ) {
			abort(403);
		}
		if ($user->can('destroy',$spot)) {
			if ($travel->allSpotsPassed()) {
				throw new SpotAlreadyPassedException();
			}
			$middleSpot = $travel->spots()->where("position", 1)->firstOrFail();
			if ($travel->status->value ===  TravelStatus::RUNNING->value && $middleSpot) {
				$middleSpot->delete();
				return response()->json(TravelResource::make($travel));
			}
			if ($travel->status->value ===  TravelStatus::RUNNING->value && $travel->getOriginSpot()) {
				throw new ProtectedSpotException();
			}
			if ($travel->status->value === TravelStatus::CANCELLED->value) {
				throw new InvalidTravelStatusForThisActionException();
			}
		}
	
		
		
	}
}
