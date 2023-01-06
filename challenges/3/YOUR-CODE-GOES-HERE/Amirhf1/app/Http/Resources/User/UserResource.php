<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    /**
     * @param $request
     * @return array[]
     */
    public function toArray($request): array
    {
        return [
            'user' => [
                'name'      => $this->name,
                'lastname'  => $this->lastname,
                'cellphone' => $this->cellphone,
                'token'     => $this->token,
            ]
        ];
    }

}
