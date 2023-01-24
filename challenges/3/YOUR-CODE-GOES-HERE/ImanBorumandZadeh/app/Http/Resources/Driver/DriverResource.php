<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{

    /**
     * @param $request
     * @return array
     */
    public function toArray( $request) : array
    {
        return [
            'id' => $this->id,
            'car_plate' => (string) $this->car_plate,
            'car_model' => (string) $this->car_model,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'status' => $this->status,
        ];
    }
}
