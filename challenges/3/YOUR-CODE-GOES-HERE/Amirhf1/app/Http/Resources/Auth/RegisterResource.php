<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'user' => [
                'cellphone' => $this->cellphone,
                'name'      => $this->name,
                'lastname'  => $this->lastname,
            ]
        ];
    }


}
