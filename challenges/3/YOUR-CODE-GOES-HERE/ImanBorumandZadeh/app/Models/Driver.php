<?php

namespace App\Models;

use App\Enums\DriverStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
