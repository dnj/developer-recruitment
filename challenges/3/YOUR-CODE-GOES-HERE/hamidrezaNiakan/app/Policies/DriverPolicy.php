<?php

namespace App\Policies;

use App\Models\Driver;
use App\Models\User;

class DriverPolicy
{

    public function update(User $user, Driver $driver): bool
    {
        return $user->id == $driver->id;
    }

    public function signup(User $user): bool
    {
        return true;
    }
}
