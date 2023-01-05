<?php

namespace App\Models;

use App\Enums\DriverStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @package App\Models
 *
 * @property int       id
 * @property string    car_model
 * @property string    car_plate
 * @property double    latitude
 * @property double    longitude
 * @property string    status
 * @property \DateTime created_at
 * @property \DateTime updated_at
 *
 */
class Driver extends Model
{
    use HasFactory;

    public static function byUser(User $user): self
    {
        return self::query()->findOrFail($user->id);
    }

    public static function isDriver(User $user): bool
    {
        return self::query()->where("id", $user->id)->exists();
    }

    public $incrementing = false;

    protected $casts = array(
        'status' => DriverStatus::class,
    );


    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'car_model',
        'car_plate',
        'latitude',
        'longitude',
        'status',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCarModel(): string
    {
        return $this->car_model;
    }

    /**
     * @param string $car_model
     */
    public function setCarModel(string $car_model): void
    {
        $this->car_model = $car_model;
    }

    /**
     * @return string
     */
    public function getCarPlate(): string
    {
        return $this->car_plate;
    }

    /**
     * @param string $car_plate
     */
    public function setCarPlate(string $car_plate): void
    {
        $this->car_plate = $car_plate;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
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
