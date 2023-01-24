<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelSpot extends Model
{
    use HasFactory;

    protected $table = "travels_spots";

    protected $fillable = [
        'travel_id',
        'latitude',
        'longitude',
        'position',
    ];


    /**
     * @return BelongsTo
     */
    public function travel() : BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }
}
