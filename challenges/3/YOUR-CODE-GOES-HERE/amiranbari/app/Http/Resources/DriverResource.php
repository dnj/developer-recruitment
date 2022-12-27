<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'car_plate' => $this->car_plate,
            'car_model' => $this->car_model,
            'status'    => $this->status,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude
        ];
    }
}
