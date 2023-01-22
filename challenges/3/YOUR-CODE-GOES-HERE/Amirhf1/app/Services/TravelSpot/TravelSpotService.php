<?php

namespace App\Services\TravelSpot;

use App\Enums\TravelStatus;
use App\Exceptions\InvalidTravelStatusForThisActionException;
use App\Exceptions\ProtectedSpotException;
use App\Exceptions\SpotAlreadyPassedException;
use App\Http\Requests\TravelSpotStoreRequest;
use App\Models\Travel;
use App\Models\TravelSpot;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class TravelSpotService extends BaseService
{
    /**
     * @var Travel
     */
    private Travel $travel;

    /**
     * @var TravelSpot
     */
    private TravelSpot $travelSpot;

    /**
     * @var TravelSpotStoreRequest
     */
    private TravelSpotStoreRequest $spotStoreRequest;

    /**
     * @param  Travel  $travel
     * @return TravelSpotService
     */
    public function setTravel(Travel $travel): TravelSpotService
    {
        $this->travel = $travel;

        return $this;
    }

    /**
     * @param  TravelSpot  $travelSpot
     * @return TravelSpotService
     */
    public function setTravelSpot(TravelSpot $travelSpot): TravelSpotService
    {
        $this->travelSpot = $travelSpot;

        return $this;
    }

    /**
     * @param  TravelSpotStoreRequest  $spotStoreRequest
     * @return TravelSpotService
     */
    public function setSpotStoreRequest(TravelSpotStoreRequest $spotStoreRequest): TravelSpotService
    {
        $this->spotStoreRequest = $spotStoreRequest;

        return $this;
    }

    /**
     * @return JsonResponse
     *
     * @throws InvalidTravelStatusForThisActionException
     * @throws SpotAlreadyPassedException
     */
    public function arrived(): JsonResponse
    {
        $this->travelSpot->setArrivedAt(Carbon::now());
        $this->travelSpot->save();

        return response()->json([
            'travel' => $this->travel
                ->with('spots')
                ->first()
                ->toArray(),
        ]);
    }

    /**
     * @return JsonResponse
     *
     * @throws InvalidTravelStatusForThisActionException
     * @throws SpotAlreadyPassedException
     */
    public function store(): JsonResponse
    {
        $travel = $this->travel->with('spots')->first();

        $maxPositions = $travel->spots->max('position');
        if ($this->positionLessThen($this->spotStoreRequest->get('position'), $maxPositions)) {
            return response()->json([
                'errors' => [
                    'position' => 'error',
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $position = ($this->getBaseQueryOfPosition($travel, $this->spotStoreRequest->get('position')));
        if (! $position->exists()) {
            throw new SpotAlreadyPassedException();
        }

        $position->increment('position');
        $this->createTravelSpot($travel->id, $this->spotStoreRequest);

        return response()->json([
            'travel' => $travel->with('spots')->first()->toArray(),
        ]);
    }

    /**
     * @throws InvalidTravelStatusForThisActionException
     * @throws ProtectedSpotException
     * @throws SpotAlreadyPassedException
     */
    public function destroy(): JsonResponse
    {
        /** @var Travel $travel */
        $travel = $this->travel->with('spots')->first();

        if ($this->travelSpot->position == 0 || (count($travel->spots) == 2)) {
            throw new ProtectedSpotException();
        }

        $travel->spots()->where('position', $this->travelSpot->position)->delete();
        $travel
            ->spots()
            ->where('position', '>', $this->travelSpot->position)
            ->whereNull('arrived_at')
            ->decrement('position');

        return response()->json([
            'travel' => $travel->with('spots')->first()->toArray(),
        ]);
    }

    /**
     * @return TravelSpotService
     *
     * @throws InvalidTravelStatusForThisActionException
     */
    public function checkTravelStatus(): TravelSpotService
    {
        if ($this->travel->status == TravelStatus::CANCELLED) {
            throw new InvalidTravelStatusForThisActionException();
        }

        return $this;
    }

    /**
     * @param  string  $pointer
     * @return $this
     */
    public function checkTravelUserId(string $pointer): TravelSpotService
    {
        if ($this->travel->{$pointer} == auth()->id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $this;
    }

    /**
     * @return $this
     *
     * @throws SpotAlreadyPassedException
     */
    public function isSpotAlreadyPassed(): TravelSpotService
    {
        if (! is_null($this->travelSpot->arrived_at)) {
            throw new SpotAlreadyPassedException();
        }

        return $this;
    }

    /**
     * @param  int  $position
     * @param  int  $lastPositions
     * @return bool
     */
    private function positionLessThen(int $position, mixed $lastPositions): bool
    {
        return $position > $lastPositions;
    }

    /**
     * @param  Travel  $travel
     * @param  int  $position
     * @return HasMany
     */
    private function getBaseQueryOfPosition(Travel $travel, int $position): HasMany
    {
        return $travel
            ->spots()
            ->where('position', '>=', $position)
            ->whereNull('arrived_at');
    }

    /**
     * @param  int  $travelId
     * @param  TravelSpotStoreRequest  $request
     * @return TravelSpot
     */
    private function createTravelSpot(int $travelId, TravelSpotStoreRequest $request): TravelSpot
    {
        $travelSpot = new TravelSpot();
        $travelSpot->travel_id = $travelId;
        $travelSpot->position = $request->position;
        $travelSpot->latitude = $request->latitude;
        $travelSpot->longitude = $request->longitude;
        $travelSpot->save();

        return $travelSpot->refresh();
    }
}
