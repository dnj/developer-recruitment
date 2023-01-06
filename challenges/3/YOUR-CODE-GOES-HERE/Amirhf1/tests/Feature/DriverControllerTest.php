<?php

namespace Tests\Feature;

use App\Enums\DriverStatus;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Concerns\TestingTravel;
use Tests\TestCase;

class DriverControllerTest extends TestCase
{

    use RefreshDatabase, TestingTravel;

    public function testSignup(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $plate = '22س55555';
        $model = 'سمند تاکسی';
        $this->postJson('/api/driver', array(
            'car_plate' => $plate,
            'car_model' => $model,
        ))
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($plate, $model) {
                $json->where("driver.car_plate", $plate);
                $json->where("driver.car_model", $model);
                $json->where("driver.status", DriverStatus::NOT_WORKING->value);
            });

        $this->postJson('/api/driver', array(
            'car_plate' => $plate,
            'car_model' => $model,
        ))
            ->assertStatus(400)
            ->assertJson(array(
                "code" => "AlreadyDriver"
            ));
    }

    public function testUpdate(): void
    {
        Travel::factory(3)->create();
        $pendingTravels = Travel::factory(2)->searchingForDriver()->create();

        $driver = Driver::factory()->create();
        Sanctum::actingAs($driver->user);

        $driverData = array(
            'latitude' => 32.7088770,
            'longitude' => 51.6607175,
            'status' => DriverStatus::WORKING->value,
        );

        $response = $this->putJson('/api/driver', $driverData)
            ->assertStatus(200)
            ->assertJson(array(
                'driver' => $driverData
            ))
            ->assertJson(function (AssertableJson $json) {
                $json->has("travels", 2);
                $json->hasAll(["travels.0.id", "travels.0.spots", "travels.1.id", "travels.1.spots"]);
                $json->etc();
            });

        foreach ($response['travels'] as $travel) {
            $this->assertTrue($pendingTravels->pluck("id")->contains($travel['id']));
        }
    }
}
