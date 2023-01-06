<?php

namespace App\Policies;

use App\Models\Driver;
use App\Models\Travel;
use App\Models\User;

class TravelPolicy
{

    public function view(User $user, Travel $travel): bool
    {
        return in_array($user->id, [$travel->passenger_id, $travel->driver_id]);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function cancel(User $user, Travel $travel): bool
    {
        return in_array($user->id, [$travel->passenger_id, $travel->driver_id]);
    }

    public function markAsPassengerOnBoard(User $user, Travel $travel): bool
    {
        return $user->id ==  $travel->driver_id;
    }

    public function markAsDone(User $user, Travel $travel): bool
    {
        return $user->id ==  $travel->driver_id;
    }

    public function take(User $user): bool
    {
        return Driver::isDriver($user);
    }
}
