<?php

namespace Tests\Feature;

use App\Models\TravelSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Concerns\TestingTravel;
use Tests\TestCase;

class TravelSpotControllerTest extends TestCase
{

    use RefreshDatabase, TestingTravel;

    public function testArrived(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, false)->create();

        $origin = $travel->getOriginSpot();
        Sanctum::actingAs($driver->user);
        $response = $this->postJson("/api/travels/{$travel->id}/spots/{$origin->id}/arrived")
            ->assertStatus(200);
        
        $found = false;
        foreach ($response['travel']['spots'] as $spot) {
            if ($spot['position'] == 0 and $spot['arrived_at']) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);

        Sanctum::actingAs($passenger);
        $response = $this->postJson("/api/travels/{$travel->id}/spots/{$origin->id}/arrived")
            ->assertStatus(403);
    }

    public function testArrivedAsPassenger(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, false)->create();
        $origin = $travel->getOriginSpot();

        Sanctum::actingAs($passenger);
        $this->postJson("/api/travels/{$travel->id}/spots/{$origin->id}/arrived")
            ->assertStatus(403);
    }

    public function testArrivedNotRunningTravel(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, false)->cancelled()->create();
        $origin = $travel->getOriginSpot();

        Sanctum::actingAs($driver->user);
        $this->postJson("/api/travels/{$travel->id}/spots/{$origin->id}/arrived")
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'InvalidTravelStatusForThisAction'
            ));
    }

    public function testArrivedWhenAlreadyArrived(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, true)->create();
        $origin = $travel->getOriginSpot();

        Sanctum::actingAs($driver->user);
        $this->postJson("/api/travels/{$travel->id}/spots/{$origin->id}/arrived")
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'SpotAlreadyPassed'
            ));
    }

    public function testStore(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, false)->create();

        Sanctum::actingAs($passenger);
        $latitude = fake()->randomFloat(5, 32.64517, 32.65077);
        $longitude = fake()->randomFloat(5, 51.66532, 51.670368);
        $response = $this->postJson("/api/travels/{$travel->id}/spots", array(
            'latitude' => $latitude,
            'longitude' => $longitude,
            'position' => 1,
        ))
            ->assertStatus(200);

        foreach ($response['travel']['spots'] as $spot) {
            if ($spot['position'] == 1) {
                $this->assertSame($latitude, $spot['latitude']);
                $this->assertSame($longitude, $spot['longitude']);
                break;
            }
        }
        
        $this->assertPositionsInRange(3, $response['travel']['spots']);
    }

    public function testStoreAsDriver(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, false)->create();

        Sanctum::actingAs($driver->user);
        $latitude = fake()->randomFloat(5, 32.64517, 32.65077);
        $longitude = fake()->randomFloat(5, 51.66532, 51.670368);
        $this->postJson("/api/travels/{$travel->id}/spots", array(
            'latitude' => $latitude,
            'longitude' => $longitude,
            'position' => 1,
        ))
            ->assertStatus(403);
    }

    public function testStoreOutOfRange(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, false)->create();

        Sanctum::actingAs($passenger);
        $this->postJson("/api/travels/{$travel->id}/spots", array(
            'latitude' => fake()->randomFloat(5, 32.64517, 32.65077),
            'longitude' => fake()->randomFloat(5, 51.66532, 51.670368),
            'position' => 3,
        ))
            ->assertStatus(422)
            ->assertJson(fn(AssertableJson $json) => $json->has("errors.position")->etc());
    }

    public function testStoreArrived(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, true, true)->create();

        Sanctum::actingAs($passenger);
        $this->postJson("/api/travels/{$travel->id}/spots", array(
            'latitude' => fake()->randomFloat(5, 32.64517, 32.65077),
            'longitude' => fake()->randomFloat(5, 51.66532, 51.670368),
            'position' => 1,
        ))
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'SpotAlreadyPassed'
            ));
    }

    public function testStoreNotRunningTravel(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, false)->cancelled()->create();

        Sanctum::actingAs($passenger);
        $this->postJson("/api/travels/{$travel->id}/spots", array(
            'latitude' => fake()->randomFloat(5, 32.64517, 32.65077),
            'longitude' => fake()->randomFloat(5, 51.66532, 51.670368),
            'position' => 1,
        ))
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'InvalidTravelStatusForThisAction'
            ));
    }

    public function testDestroy(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver)
            ->has(TravelSpot::factory()->withPosition(2), 'spots')
            ->create();
        $middleSpot = $travel->spots()->where("position", 1)->firstOrFail();
    
        Sanctum::actingAs($passenger);
        $response = $this->deleteJson("/api/travels/{$travel->id}/spots/{$middleSpot->id}")
            ->assertStatus(200);
        $this->assertPositionsInRange(2, $response['travel']['spots']);
    }

    public function testDestroyNotRunningTravel(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver)
            ->cancelled()
            ->create();
        $spot = $travel->spots()->where("position", 1)->firstOrFail();
    
        Sanctum::actingAs($passenger);
        $this->deleteJson("/api/travels/{$travel->id}/spots/{$spot->id}")
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'InvalidTravelStatusForThisAction'
            ));
    }

    public function testDestroyAsDriver(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver)->create();
        $spot = $travel->spots()->where("position", 1)->firstOrFail();
    
        Sanctum::actingAs($driver->user);
        $this->deleteJson("/api/travels/{$travel->id}/spots/{$spot->id}")
            ->assertStatus(403);
    }

    public function testDestroyArrived(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, true, true)->create();
        $spot = $travel->spots()->where("position", 1)->firstOrFail();
    
        Sanctum::actingAs($passenger);
        $this->deleteJson("/api/travels/{$travel->id}/spots/{$spot->id}")
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'SpotAlreadyPassed'
            ));
    }

    public function testDestroyOrigin(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, false)->create();
        $spot = $travel->getOriginSpot();
    
        Sanctum::actingAs($passenger);
        $this->deleteJson("/api/travels/{$travel->id}/spots/{$spot->id}")
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'ProtectedSpot'
            ));
    }

    public function testDestroyLastSpot(): void
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, true)->create();
        $spot = $travel->spots()->where("position", 1)->firstOrFail();
    
        Sanctum::actingAs($passenger);
        $response = $this->deleteJson("/api/travels/{$travel->id}/spots/{$spot->id}")
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'ProtectedSpot'
            ));
    }

    protected function assertPositionsInRange(int $expactedSpots, array $spots): void {
        $this->assertCount($expactedSpots, $spots);

        $positions = array_column($spots, 'position');
        sort($positions);
        $this->assertSame(range(0, $expactedSpots - 1), $positions);
    }
}
