<?php

namespace Database\Factories;

use App\Enums\TravelStatus;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{

    protected $model = Travel::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'passenger_id' => User::factory(),
            'driver_id' => Driver::factory(),
            'status' => fake()->randomElement([TravelStatus::DONE, TravelStatus::RUNNING, TravelStatus::CANCELLED]),
        ];
    }

    public function withPassenger(User $user) {
        return $this->state(fn () => [
            'passenger_id' => $user,
        ]);
    }

    public function withDriver(Driver $driver) {
        return $this->state(fn () => [
            'driver_id' => $driver,
        ]);
    }

    public function searchingForDriver(): static
    {
        return $this->state(fn() => [
            'driver_id' => null,
            'status' => TravelStatus::SEARCHING_FOR_DRIVER,
        ]);
    }

    public function done(): static
    {
        return $this->state(fn () => [
            'status' => TravelStatus::DONE,
        ]);
    }

    public function running(): static
    {
        return $this->state(fn () => [
            'status' => TravelStatus::RUNNING,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => TravelStatus::CANCELLED,
        ]);
    }
}
