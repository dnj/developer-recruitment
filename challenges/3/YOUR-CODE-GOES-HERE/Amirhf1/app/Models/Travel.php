<?php

namespace App\Models;

use App\Enums\TravelEventType;
use App\Enums\TravelStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @package App\Models
 *
 * @property int id
 * @property int passenger_id
 * @property int driver_id
 * @property string status
 * @property \DateTime created_at
 * @property \DateTime updated_at
 *
 */
class Travel extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public static function userHasActiveTravel(User $user): bool
    {
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

    public function passengerIsInCar(): bool
    {
        return $this->events()->where("type", TravelEventType::PASSENGER_ONBOARD)->exists();
    }

    public function driverHasArrivedToOrigin(): bool
    {
        return $this->spots()->where("position", 0)->value("arrived_at") !== null;
    }

    public function allSpotsPassed(): bool
    {
        return $this->spots()->whereNull("arrived_at")->doesntExist();
    }

    public function getOriginSpot(): TravelSpot
    {
        return $this->spots()
            ->where("position", 0)
            ->firstOrFail();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPassengerId(): int
    {
        return $this->passenger_id;
    }

    /**
     * @param int $passenger_id
     */
    public function setPassengerId(int $passenger_id): void
    {
        $this->passenger_id = $passenger_id;
    }

    /**
     * @return int
     */
    public function getDriverId(): int
    {
        return $this->driver_id;
    }

    /**
     * @param int $driver_id
     */
    public function setDriverId(int $driver_id): void
    {
        $this->driver_id = $driver_id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

}