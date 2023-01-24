<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    /**
     * @param $request
     * @return array
     */
    public function toArray( $request) : array
    {
        return [
            'name' => (string) $this->name,
            'lastname' => (string) $this->lastname,
            'cellphone' => (string) $this->cellphone,
        ];
    }
}
