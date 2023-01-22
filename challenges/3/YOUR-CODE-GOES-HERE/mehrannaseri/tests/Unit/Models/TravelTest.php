<?php

namespace Tests\Unit\Models;

use App\Models\Driver;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelTest extends TestCase
{
	use RefreshDatabase;

	public function testRelations() {
		$travel = Travel::factory()->create();
		$this->assertInstanceOf(User::class, $travel->passenger);
		$this->assertInstanceOf(Driver::class, $travel->driver);
	}
}
