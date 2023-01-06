<?php

namespace App\Http\Controllers;



use App\Enums\TravelEventType;
use App\Enums\TravelStatus;
use App\Exceptions\ActiveTravelException;
use App\Exceptions\AllSpotsDidNotPassException;
use App\Exceptions\CannotCancelFinishedTravelException;
use App\Exceptions\CannotCancelRunningTravelException;
use App\Exceptions\CarDoesNotArrivedAtOriginException;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Http\Requests\Travel\DoneTravelRequest;
use App\Http\Requests\Travel\PassengerOnBoardRequest;
use App\Http\Requests\Travel\TakeTravelRequest;
use App\Http\Requests\Travel\TravelCancelRequest;
use App\Http\Requests\Travel\TravelStoreRequest;
use App\Http\Requests\Travel\ViewTravelRequest;
use App\Http\Resources\Travel\TravelResource;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Throwable;

class TravelController extends Controller
{

    /**
     * @param ViewTravelRequest $request
     * @return JsonResponse
     */
    public function view( ViewTravelRequest $request) : JsonResponse
    {
        return $this->apiResponse([
            'travel' => new TravelResource(
                Travel::find($request->validated()['id'])
            )]
        );
	}


    /**
     * @param TravelStoreRequest $request
     * @return JsonResponse
     * @throws ActiveTravelException
     * @throws Throwable
     */
    public function store( TravelStoreRequest $request) : JsonResponse
    {
        $user = auth()->user();
        throw_if(
            Travel::userHasActiveTravel($user),
            new ActiveTravelException()
        );

        return $this->apiResponse([
            'travel' => new TravelResource(
                $this->createTravelAndSpots(
                    $user,
                    $request->validated())->load('spots')
            )], 201
        );
	}


    /**
     * @param TravelCancelRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function cancel( TravelCancelRequest $request) : JsonResponse
    {
        $travel = Travel::find($request->travel_id);
        $userIsDriver = Driver::isDriver(auth()->user());

        //check requirement for access to cancel
        $this->checkAccessToCancelTravel($travel, $userIsDriver);

        //update model
        $this->updateTravelStatusToCancel($userIsDriver, $travel);

        return $this->apiResponse(['travel' => new TravelResource($travel)]);
	}


    /**
     * @param PassengerOnBoardRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function passengerOnBoard( PassengerOnBoardRequest $request) : JsonResponse
    {
        $travel = Travel::find($request->id);

        $this->checkPassengerOnBoard($travel);

        $travel->events()->create(['type' => TravelEventType::PASSENGER_ONBOARD]);

        return $this->apiResponse([
            'travel' => new TravelResource($travel->load('events'))
        ]);
	}


    /**
     * @param DoneTravelRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function done( DoneTravelRequest $request) : JsonResponse
    {
        $travel = Travel::find($request->id);

        //check requirement
        $this->checkRequirementForChangeStatusToDone($travel);

        //save new status and create event
        $travel->status = TravelStatus::DONE;
        $travel->save();
        $travel->events()->create(['type' => TravelEventType::DONE]);

        return $this->apiResponse([
            'travel' => new TravelResource($travel->load('events'))
        ]);
	}


    /**
     * @param TakeTravelRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function take( TakeTravelRequest $request) : JsonResponse
    {
        $user = auth()->user();
        $travel = Travel::find($request->id);

        //check requirement for accept take travel
        $this->checkTakeRequirement($user, $travel);

        //update driver set to user->id
        $travel->driver_id = $user->id;
        $travel->save();

        return $this->apiResponse(['travel' => new TravelResource($travel)]);
	}



    //private functions
    /**
     * @param User  $user
     * @param array $request
     * @return Model
     * @throws ActiveTravelException
     */
    private function createTravelAndSpots( User $user, array $request) : Model
    {
        DB::beginTransaction();
        try {
            $travel = $user->travels()->create($request);
            $travel->spots()->createMany($request['spots'] ?? []);
            DB::commit();
        } catch(Exception) {
            DB::rollBack();
            throw new ActiveTravelException();
        }
        return $travel;
    }


    /**
     * @param Travel $travel
     * @param bool   $userIsDriver
     * @return void
     * @throws Throwable
     */
    private function checkAccessToCancelTravel( Travel $travel, bool $userIsDriver) : void
    {
        throw_if(
            $travel->passengerIsInCar() ||
            (!$userIsDriver && $travel->status == TravelStatus::RUNNING ),
            new CannotCancelRunningTravelException()
        );

        throw_if(
            in_array($travel->status, [
                TravelStatus::CANCELLED,
                TravelStatus::DONE
            ]),
            new CannotCancelFinishedTravelException()
        );
    }


    /**
     * @param bool   $userIsDriver
     * @param Travel $travel
     * @return void
     */
    private function updateTravelStatusToCancel( bool $userIsDriver, Travel $travel) : void
    {
        if (
            ($userIsDriver && $travel->status == TravelStatus::RUNNING) ||
            $travel->status == TravelStatus::SEARCHING_FOR_DRIVER
        ) {
            $travel->status = TravelStatus::CANCELLED;
            $travel->save();
        }
    }


    /**
     * @param Travel $travel
     * @return void
     * @throws Throwable
     */
    private function checkPassengerOnBoard( Travel $travel) : void
    {
        throw_if(
            !$travel->driverHasArrivedToOrigin(),
            new CarDoesNotArrivedAtOriginException()
        );

        throw_if( (
                $travel->passengerIsInCar() ||
                $travel->status == TravelStatus::DONE
            ),
            new InvalidTravelStatusForThisActionException()
        );
    }


    /**
     * @param User   $user
     * @param Travel $travel
     * @return void
     * @throws Throwable
     */
    private function checkTakeRequirement( User $user, Travel $travel) : void
    {
        throw_if(
            Travel::userHasActiveTravel($user),
            new ActiveTravelException()
        );

        throw_if(
            $travel->status != TravelStatus::SEARCHING_FOR_DRIVER,
            new InvalidTravelStatusForThisActionException()
        );
    }


    /**
     * @param Travel $travel
     * @return void
     * @throws Throwable
     */
    private function checkRequirementForChangeStatusToDone( Travel $travel) : void
    {
        throw_if(
            !$travel->allSpotsPassed(),
            new AllSpotsDidNotPassException()
        );
        throw_if(
            $travel->status == TravelStatus::DONE,
            new InvalidTravelStatusForThisActionException()
        );

    }
}
