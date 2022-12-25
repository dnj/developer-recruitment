<?php

namespace App\Models;

use App\Enums\TravelEventType;
use App\Enums\TravelStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;

    public static function userHasActiveTravel(User $user): bool {
        return self::query()
            ->whereIn("status", [TravelStatus::RUNNING, TravelStatus::SEARCHING_FOR_DRIVER])
            ->where(function ($q) use ($user) {
                $q->where("passenger_id", $user->id);
                $q->orWhere("driver_id", $user->id);
            })
            ->exists();
    }

    protected $table = "travels";
    protected $casts = array(
        'status' => TravelStatus::class,
    );

    public function passenger()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function events()
    {
        return $this->hasMany(TravelEvent::class);
    }

    public function spots()
    {
        return $this->hasMany(TravelSpot::class);
    }

    public function passengerIsInCar(): bool {
        return $this->events()->where("type", TravelEventType::PASSENGER_ONBOARD)->exists();
    }

    public function driverHasArrivedToOrigin(): bool {
        return $this->spots()->where("position", 0)->value("arrived_at") !== null;
    }

    public function allSpotsPassed(): bool {
        return $this->spots()->whereNull("arrived_at")->doesntExist();
    }

    public function getOriginSpot(): TravelSpot
    {
        return $this->spots()
            ->where("position", 0)
            ->firstOrFail();
    }
}
