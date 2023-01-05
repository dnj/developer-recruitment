<?php

namespace App\Services\User;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\BaseService;

class UserService extends BaseService
{
    /**
     * @param User $user
     * @return mixed
     */
    public function user(User $user)
    {
        return $user['token'] = $user->currentAccessToken()->token;
    }
}
