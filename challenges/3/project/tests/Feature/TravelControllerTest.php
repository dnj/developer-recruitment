<?php

namespace Tests\Feature;

use App\Enums\TravelEventType;
use App\Enums\TravelStatus;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Concerns\TestingTravel;
use Tests\TestCase;

class TravelControllerTest extends TestCase
{


    private const TWO_SPOTS = [
        array(
            'position' => 0,
            'latitude'  => 32.70946862,
            'longitude' => 51.66043121,
        ),
        array(
            'position' => 1,
            'latitude'  => 32.67596261,
            'longitude' => 51.65045944,
        ),
    ];

    use RefreshDatabase, TestingTravel;

    public function testStore(): void
    {
        $passenger = User::factory()->create();
        Sanctum::actingAs($passenger);


        $this->postJson('/api/travels', array(
            'spots' => self::TWO_SPOTS,
        ))
            ->assertStatus(201)
            ->assertJson(array(
                'travel' => array(
                    'spots' => self::TWO_SPOTS,
                    'passenger_id' => $passenger->id,
                    'status' => TravelStatus::SEARCHING_FOR_DRIVER->value,
                )
            ));
    }

    public function testStoreWithBadPositions(): void
    {
        $passenger = User::factory()->create();
        Sanctum::actingAs($passenger);


        $this->postJson('/api/travels', array(
            'spots' => array(
                array(
                    'position' => 1,
                    'latitude'  => 32.70946862,
                    'longitude' => 51.66043121,
                ),
                array(
                    'position' => 2,
                    'latitude'  => 32.67596261,
                    'longitude' => 51.65045944,
                )
            ),
        ))
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) => $json->has("errors.spots")->etc());
    }

    public function testStoreWhenHaveActiveTravel()
    {
        $user = User::factory()->create();
        Travel::factory()
            ->withPassenger($user)
            ->running()
            ->create();

        Sanctum::actingAs($user);
        $this->postJson('/api/travels', ['spots' => self::TWO_SPOTS])
            ->assertStatus(400)
            ->assertJson(array(
                "code" => "ActiveTravel"
            ));
    }

    public function testCancelSearchingForDriverAsPassenger()
    {
        $passenger = User::factory()->create();
        $travel = Travel::factory()
            ->withPassenger($passenger)
            ->searchingForDriver()
            ->create();

        Sanctum::actingAs($passenger);
        $this->postJson("/api/travels/{$travel->id}/cancel")
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where("travel.status", TravelStatus::CANCELLED->value);
                $json->etc();
            });
    }

    public function testCancelFinishedTravel()
    {
        $passenger = User::factory()->create();
        Sanctum::actingAs($passenger);

        $travel = Travel::factory()
            ->withPassenger($passenger)
            ->cancelled()
            ->create();

        $this->postJson("/api/travels/{$travel->id}/cancel")
            ->assertStatus(400)
            ->assertJson(array(
                "code" => "CannotCancelFinishedTravel"
            ));

        $travel = Travel::factory()
            ->withPassenger($passenger)
            ->done()
            ->create();

        $this->postJson("/api/travels/{$travel->id}/cancel")
            ->assertStatus(400)
            ->assertJson(array(
                "code" => "CannotCancelFinishedTravel"
            ));
    }

    public function testCancelOnboardPassenger()
    {
        [$passenger, $driver] = $this->createPassengerDriver();

        $travel = $this->runningTravel($passenger, $driver)
            ->has(TravelEvent::factory()->passengerOnBoard(), 'events')
            ->create();

        foreach ([$passenger, $driver->user] as $user) {
            Sanctum::actingAs($user);
            $this->postJson("/api/travels/{$travel->id}/cancel")
                ->assertStatus(400)
                ->assertJson(array(
                    "code" => "CannotCancelRunningTravel"
                ));
        }
    }

    public function testCancelArrivedCar()
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver)->create();

        Sanctum::actingAs($passenger);
        $this->postJson("/api/travels/{$travel->id}/cancel")
            ->assertStatus(400)
            ->assertJson(array(
                "code" => "CannotCancelRunningTravel"
            ));


        Sanctum::actingAs($driver->user);
        $this->postJson("/api/travels/{$travel->id}/cancel")
            ->assertStatus(200)
            ->assertJson(array(
                "travel" => array(
                    'status' => TravelStatus::CANCELLED->value
                )
            ));
    }

    public function testView()
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver)->create();

        foreach ([$passenger, $driver->user] as $user) {
            Sanctum::actingAs($user);
            $this->getJson("/api/travels/{$travel->id}")
                ->assertStatus(200)
                ->assertJson(array(
                    "travel" => array(
                        'id' => $travel->id
                    )
                ));
        }
    }

    public function testPassengerOnBoard()
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver)->create();

        Sanctum::actingAs($driver->user);
        $response = $this->postJson("/api/travels/{$travel->id}/passenger-on-board")->assertStatus(200);

        $found = false;
        foreach ($response['travel']['events'] as $e) {
            if ($e['type'] == TravelEventType::PASSENGER_ONBOARD->value) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);

        $this->postJson("/api/travels/{$travel->id}/passenger-on-board")
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'InvalidTravelStatusForThisAction'
            ));
    }

    public function testPassengerOnBoardAsPassenger()
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver)->create();

        Sanctum::actingAs($passenger);
        $this->postJson("/api/travels/{$travel->id}/passenger-on-board")->assertStatus(403);
    }

    public function testPassengerOnBoardWhenCarIsNotArrived()
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, false)->create();

        Sanctum::actingAs($driver->user);
        $this->postJson("/api/travels/{$travel->id}/passenger-on-board")
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'CarDoesNotArrivedAtOrigin'
            ));
    }

    public function testPassengerOnBoardFinishedTravel()
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver)->done()->create();

        Sanctum::actingAs($driver->user);
        $this->postJson("/api/travels/{$travel->id}/passenger-on-board")
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'InvalidTravelStatusForThisAction'
            ));
    }

    public function testDone()
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, true, true)
            ->has(TravelEvent::factory()->passengerOnBoard(), 'events')
            ->create();

        Sanctum::actingAs($driver->user);
        $response = $this->postJson("/api/travels/{$travel->id}/done")
            ->assertStatus(200)
            ->assertJson(array(
                "travel" => array(
                    "status" => TravelStatus::DONE->value,
                )
            ));

        $found = false;
        foreach ($response['travel']['events'] as $e) {
            if ($e['type'] == TravelEventType::DONE->value) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);

        $this->postJson("/api/travels/{$travel->id}/done")
            ->assertStatus(400)
            ->assertJson(array(
                'code' => 'InvalidTravelStatusForThisAction'
            ));
    }

    public function testDoneAsPassenger()
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, true, true)->create();

        Sanctum::actingAs($passenger);
        $this->postJson("/api/travels/{$travel->id}/done")->assertStatus(403);
    }

    public function testDoneWhenSpotsSkipped()
    {
        [$passenger, $driver] = $this->createPassengerDriver();
        $travel = $this->runningTravel($passenger, $driver, true, false)->create();

        Sanctum::actingAs($driver->user);
        $this->postJson("/api/travels/{$travel->id}/done")
            ->assertStatus(400)
            ->assertJson(array(
                "code" => 'AllSpotsDidNotPass'
            ));
    }

    public function testTake()
    {
        $driver = Driver::factory()->create();
        $travel = Travel::factory()->searchingForDriver()->create();

        Sanctum::actingAs($driver->user);
        $this->postJson("/api/travels/{$travel->id}/take")
            ->assertStatus(200)
            ->assertJson(array(
                "travel" => array(
                    'id' => $travel->id,
                    'driver_id' => $driver->id,
                    'status' => TravelStatus::SEARCHING_FOR_DRIVER->value,
                )
            ));
    }

    public function testTakeCancelledTravel()
    {
        $driver = Driver::factory()->create();
        $travel = Travel::factory()->cancelled()->create();

        Sanctum::actingAs($driver->user);
        $this->postJson("/api/travels/{$travel->id}/take")
            ->assertStatus(400)
            ->assertJson(array(
                "code" => 'InvalidTravelStatusForThisAction'
            ));
    }

    public function testTakeWithActiveTravel()
    {
        $driver = Driver::factory()->create();
        Travel::factory()
            ->withDriver($driver)
            ->running()
            ->create();
        
        $travel = Travel::factory()->searchingForDriver()->create();

        Sanctum::actingAs($driver->user);
        $this->postJson("/api/travels/{$travel->id}/take")
            ->assertStatus(400)
            ->assertJson(array(
                "code" => 'ActiveTravel'
            ));
    }
}
