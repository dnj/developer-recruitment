<?php

namespace App\Http\Resources\Travel;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelResource extends JsonResource
{

    /**
     * @param $request
     * @return array
     */
    public function toArray( $request) : array
    {
        return [
            "id" => $this->id,
            "passenger_id" => $this->passenger_id,
            "driver_id" => $this->driver_id,
            "status" => $this->status,
            "spots" => $this->when( $this->relationLoaded('spots'),
                function () {
                return  TravelSpotResource::collection($this->spots);
            }),
            "events" =>  $this->when( $this->relationLoaded('events'),
                function () {
                    return TravelEventResource::collection($this->events);
            }),
        ];
    }
}
