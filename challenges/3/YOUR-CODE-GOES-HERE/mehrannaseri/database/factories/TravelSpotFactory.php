<?php

namespace Database\Factories;

use App\Enums\DriverStatus;
use App\Enums\TravelStatus;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelSpot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class TravelSpotFactory extends Factory
{

    protected $model = TravelSpot::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'travel_id' => Travel::factory(),
            'position' => function (array $attributes) {
                $spots = TravelSpot::query()->where("travel_id", $attributes['travel_id'])->count();

                return $spots + 1;
            },
            'latitude' => fake()->randomFloat(5, 32.64517, 32.65077),
            'longitude' => fake()->randomFloat(5, 51.66532, 51.670368),
            'arrived_at' => null,
        ];
    }

    public function arrived(): static
    {
        return $this->state(fn() => [
            'arrived_at' => fake()->dateTime(),
        ]);
    }

    public function withPosition(int $position) {
        return $this->state(fn () => [
            'position' => $position,
        ]);
    }
}
