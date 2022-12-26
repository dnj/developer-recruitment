<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray ( $request ) {
		return [
			'id' => $this->id ,
			'car_plate' => $this->car_plate ,
			'car_model' => $this->car_model ,
			'latitude' => $this->latitude ,
			'longitude' => $this->longitude ,
			'status' => $this->status ,
			'user' => UserResource::make($this->whenLoaded('user')),
		];
	}
}
