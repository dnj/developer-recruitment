<?php

namespace Database\Factories;

use App\Enums\DriverStatus;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{

    protected $model = Driver::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => User::factory(),
            'car_model' => fake()->words(2, true),
            'car_plate' => fake()->randomDigitNot(0) . fake()->randomDigitNot(0) . fake()->randomLetter() . fake()->randomDigitNot(0) . fake()->randomDigitNot(0) . fake()->randomDigitNot(0) . fake()->randomDigitNot(0) . fake()->randomDigitNot(0),
            'latitude' => fake()->randomFloat(5, 32.64517, 32.65077),
            'longitude' => fake()->randomFloat(5, 51.66532, 51.670368),
            'status' => fake()->randomElement([DriverStatus::WORKING, DriverStatus::NOT_WORKING]),
        ];
    }

    public function notWorking(): static
    {
        return $this->state(function () {
            return [
                'status' => DriverStatus::NOT_WORKING,
            ];
        });
    }

    public function working(): static
    {
        return $this->state(function () {
            return [
                'status' => DriverStatus::WORKING,
            ];
        });
    }
}
