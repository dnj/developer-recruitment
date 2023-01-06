<?php

namespace App\Http\Resources\Travel;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelEventResource extends JsonResource
{

    /**
     * @param $request
     * @return array
     */
    public function toArray( $request) : array
    {
        return [
            "id" => $this->id,
            "type" => $this->type,
        ];
    }
}
