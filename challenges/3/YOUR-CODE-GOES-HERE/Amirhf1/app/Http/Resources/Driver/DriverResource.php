<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "driver" => [
                'car_plate' => $this->car_plate,
                'car_model' => $this->car_model,
                'status'    => $this->status,
            ]
        ];
    }
}
