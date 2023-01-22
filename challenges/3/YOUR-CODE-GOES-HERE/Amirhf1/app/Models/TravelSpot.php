<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class User
 *
 * @package App\Models
 *
 * @property int       id
 * @property int       travel_id
 * @property int       position
 * @property double    latitude
 * @property double    longitude
 * @property string    status
 * @property \DateTime created_at
 * @property \DateTime updated_at
 *
 */
class TravelSpot extends Model
{
    use HasFactory;

    protected $table = "travels_spots";

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * @param int $travel_id
     */
    public function setTravelId(int $travel_id): void
    {
        $this->travel_id = $travel_id;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @param Carbon $carbon
     * @return $this
     */
    public function setArrivedAt(Carbon $carbon): static
    {
        $this->arrived_at = $carbon;

        return $this;
    }
}
