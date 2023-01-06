<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'car_model' => $this->car_model,
            'car_plate' => $this->car_plate,
            'status' => $this->status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }
}
