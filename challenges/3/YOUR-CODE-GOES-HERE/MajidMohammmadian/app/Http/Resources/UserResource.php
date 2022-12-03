<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'user' => [
                'name'      => $this->name,
                'lastname'  => $this->lastname,
                'cellphone' => $this->cellphone
            ]
        ];

        return $data;
    }
}
