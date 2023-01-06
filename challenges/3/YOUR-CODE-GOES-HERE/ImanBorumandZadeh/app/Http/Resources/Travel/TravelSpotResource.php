<?php

namespace App\Http\Resources\Travel;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelSpotResource extends JsonResource
{

    /**
     * @param $request
     * @return array
     */
    public function toArray( $request) : array
    {
        return [
            "id" => $this->id,
            "position" => $this->position,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "arrived_at" => $this->arrived_at,
        ];
    }
}
