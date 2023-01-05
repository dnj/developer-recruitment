<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseService
{


    /**
     * @param $parameter
     * @return User
     */
    public function register($parameter): User
    {
        $user = new User();
        $user->setCellphone($parameter['cellphone']);
        $user->setName($parameter['name']);
        $user->setLastname($parameter['lastname']);
        $user->setPassword(Hash::make($parameter['password']));
        $user->save();

        $user['token'] = $user->createToken('api-token')->plainTextToken;

        return $user;
    }

}
