<?php

namespace App\Models;

use App\Enums\TravelEventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelEvent extends Model
{
    const UPDATED_AT = null;

    use HasFactory;

    protected $table = "travels_events";
    protected $casts = array(
        'type' => TravelEventType::class,
    );

    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }
}
