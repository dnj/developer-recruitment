<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'cellphone',
        'lastname',
        'password',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'password'
    ];


    //relations
    /**
     * @return BelongsTo
     */
    public function driver() : BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }


    /**
     * @return HasMany
     */
    public function travels() : HasMany
    {
        return $this->hasMany(Travel::class, 'passenger_id');
    }




}
