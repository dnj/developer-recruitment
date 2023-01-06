<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var User|UserResource */
        return [
            'id' => $this->id,
            'cellphone' => $this->cellphone,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'token' => $this->token
        ];
    }
}
