<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    use RefreshDatabase;

    public function testRegister(): void
    {
        $response = $this->postJson('/api/register', array(
            'cellphone' => '09123456789',
            'name' => 'علی',
            'lastname' => 'اکبری',
            'password' => '123456'
        ));

        $response
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where("user.cellphone", "09123456789");
                $json->where("user.name", "علی");
                $json->where("user.lastname", "اکبری");
                $json->missing('user.password');
            });

        $response = $this->postJson('/api/register', array(
            'cellphone' => '009123456789',
            'password' => '12345'
        ))
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) => 
                $json->hasAll(["errors.cellphone", "errors.name","errors.lastname", "errors.password"])->etc()
            );
    }

    public function testUser(): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson("/api/user")
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($user) {
                $json->where("user.cellphone", $user->cellphone);
                $json->where("user.name", $user->name);
                $json->where("user.lastname", $user->lastname);
                $json->missing('user.password');
            });
    }
}
