<?php

namespace App\Policies;

use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelSpot;
use App\Models\User;

class TravelSpotPolicy
{

    public function create(User $user, Travel $travel): bool
    {
        return $travel->passenger_id == $user->id;
    }

    public function markAsArrived(User $user, TravelSpot $spot): bool
    {
        return $spot->travel->driver_id == $user->id;
    }

    public function destroy(User $user, TravelSpot $spot): bool
    {
        return $spot->travel->passenger_id == $user->id;
    }
}
