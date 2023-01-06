<?php

namespace App\Http\Resources\Travel;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        $this->resource->load(
            ["spots", "events"]
        );

        return array(
            'travel' => parent::toArray($request)
        );
    }
}
