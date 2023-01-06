<?php

namespace Database\Factories;

use App\Enums\TravelEventType;
use App\Models\Travel;
use App\Models\TravelEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TravelEvent>
 */
class TravelEventFactory extends Factory
{

    protected $model = TravelEvent::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'travel_id' => Travel::factory(),
            'type' => fake()->randomElement([
                TravelEventType::ACCEPT_BY_DRIVER,
                TravelEventType::PASSENGER_ONBOARD,
                TravelEventType::CANCEL,
                TravelEventType::DONE,
            ]),
        ];
    }

    public function acceptByDriver(): static
    {
        return $this->state(fn() => [
            'type' => TravelEventType::ACCEPT_BY_DRIVER,
        ]);
    }

    public function passengerOnBoard(): static
    {
        return $this->state(fn() => [
            'type' => TravelEventType::PASSENGER_ONBOARD,
        ]);
    }

    public function cancel(): static
    {
        return $this->state(fn() => [
            'type' => TravelEventType::CANCEL,
        ]);
    }

    public function done(): static
    {
        return $this->state(fn() => [
            'type' => TravelEventType::DONE,
        ]);
    }
}
