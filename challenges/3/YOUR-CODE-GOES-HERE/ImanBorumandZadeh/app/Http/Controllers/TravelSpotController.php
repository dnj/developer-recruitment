<?php

namespace App\Http\Controllers;

use App\Enums\TravelStatus;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Exceptions\ProtectedSpotException;
use App\Exceptions\SpotAlreadyPassedException;
use App\Http\Requests\Travel\DestroyTravelSpotRequest;
use App\Http\Requests\Travel\StoreTravelSpotRequest;
use App\Http\Requests\Travel\TravelArrivedRequest;
use App\Http\Resources\Travel\TravelResource;
use App\Models\Travel;
use App\Models\TravelSpot;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Throwable;

class TravelSpotController extends Controller
{
    private Travel $travel;


    /**
     * @param TravelArrivedRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function arrived( TravelArrivedRequest $request) : JsonResponse
    {
        $this->travel = Travel::find($request->travel_id);

        $this->checkArrivedRequirement();

        //update arrived time
        $spot = $this->travel->getOriginSpot();
        $spot->arrived_at = Carbon::now();
        $spot->save();

        return $this->apiResponse([
            'travel' => new TravelResource($this->travel->load('spots'))
        ]);
	}


    /**
     * @param StoreTravelSpotRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store( StoreTravelSpotRequest $request) : JsonResponse
    {
       $this->travel = Travel::find($request->travel_id);

       //check requirement for store new spot
       $this->checkStoreRequirement();

       $this->updatePositionsOfSpots($request->position);

       //create new spot
       $this->travel->spots()->create($request->validated());

       return $this->apiResponse([
           'travel' => new TravelResource($this->travel->load('spots'))
       ]);
	}


    /**
     * @param DestroyTravelSpotRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy( DestroyTravelSpotRequest $request) : JsonResponse
    {
        $this->travel = Travel::withCount('spots')->find($request->travel_id);

        //check requirement for delete Spot
        $this->checkRequirementDestroy(TravelSpot::find($request->spot_id));

        //delete travelSpot
        TravelSpot::where('position', $request->spot_id)->delete();

        return $this->apiResponse([
            'travel' => new TravelResource($this->travel->load('spots'))
        ]);
    }



    //private functions

    /**
     * @return void
     * @throws Throwable
     */
    private function checkArrivedRequirement() : void
    {
        throw_if(
            $this->travel->driverHasArrivedToOrigin(),
            new SpotAlreadyPassedException()
        );

        throw_if(
            $this->travel->status == TravelStatus::CANCELLED,
            new InvalidTravelStatusForThisActionException()
        );
    }


    /**
     * @return void
     * @throws Throwable
     */
    private function checkStoreRequirement() : void
    {
        throw_if(
            $this->travel->status == TravelStatus::CANCELLED,
            new InvalidTravelStatusForThisActionException()
        );

        throw_if(
            $this->travel->allSpotsPassed(),
            new SpotAlreadyPassedException()
        );
    }


    /**
     * @param int $position
     * @return void
     */
    private function updatePositionsOfSpots( int $position) : void
    {
        $this->travel->spots()
                     ->where('position', '>=', $position)
                     ->increment('position');
    }


    /**
     * @param TravelSpot $spot
     * @return void
     * @throws Throwable
     */
    private function checkRequirementDestroy( TravelSpot $spot) : void
    {
        throw_if(
            !($this->travel->status == TravelStatus::RUNNING),
            new InvalidTravelStatusForThisActionException()
        );

        throw_if(
            $this->travel->allSpotsPassed(),
            new SpotAlreadyPassedException()
        );

        throw_if((
            $this->travel->getOriginSpot()->id == $spot->id ||
            ($this->travel->spots_count - 1) == $spot->position
        ),
            new ProtectedSpotException()
        );
    }
}
