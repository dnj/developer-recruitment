<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Exceptions\ProtectedSpotException;
use App\Exceptions\SpotAlreadyPassedException;
use App\Http\Requests\TravelSpotStoreRequest;
use App\Http\Resources\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelSpot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TravelSpotController extends Controller {
	public function arrived ( Travel $travel , TravelSpot $spot , Request $request ) {
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
			$origin_Spot = $travel->getOriginSpot();
			$origin_Spot->arrived_at = Carbon::now();
			$origin_Spot->save();
			
			return response()->json(TravelResource::make($travel));
		}
	}
	
	public function store ( Travel $travel , TravelSpotStoreRequest $request ) {
		$user = $request->user();
		if ( Driver::isDriver($user) ) {
			abort(403);
		}
		if ( $user->can('create' , $travel) ) {
			if ( $travel->allSpotsPassed() ) {
				throw new SpotAlreadyPassedException();
			}
			if ( $travel->status->value == TravelStatus::CANCELLED->value ) {
				throw new InvalidTravelStatusForThisActionException();
			}
			foreach ( $travel->spots as $spot ) {
				
				if ( $spot->position >= $request->position ) {
					
					$travel->spots()
						   ->where('position' , $request->position)
						   ->increment('position');
					break;
				}
			}
			$travel->spots()
				   ->create($request->toArray());
			$travel = Travel::query()
							->where('id' , $travel->id)
							->with('spots')
							->first();
			
			return response()->json([
										'travel' => [
											'spots' => $travel->spots ,
										] ,
									]);
		}
	}
	
	public function destroy ( Travel $travel , $spot , Request $request ) {
		
		$spot_deleted = TravelSpot::query()
								  ->findOrFail($spot);
		$user = $request->user();
		if ( Driver::isDriver($user) ) {
			abort(403);
		}
		if ( $user->can('destroy' , $spot_deleted) ) {
			if ( !( $travel->status->value === TravelStatus::RUNNING->value ) ) {
				throw new InvalidTravelStatusForThisActionException();
			}
			if ( $travel->allSpotsPassed() ) {
				throw  new SpotAlreadyPassedException();
			}
			$origin_Spot = $travel->getOriginSpot();
			if ( $origin_Spot->id == $spot ) {
				throw  new ProtectedSpotException();
			}
			$spot_deleted = TravelSpot::query()
									  ->findOrFail($spot);
			if ( ( count($travel->spots) - 1 ) == $spot_deleted->position ) {
				
				throw  new ProtectedSpotException();
			}
			TravelSpot::query()
					  ->where('position' , $spot)
					  ->delete();
			
			return response()->json([
										'travel' => $travel->load('spots') ,
									]);
		}
	}
}
