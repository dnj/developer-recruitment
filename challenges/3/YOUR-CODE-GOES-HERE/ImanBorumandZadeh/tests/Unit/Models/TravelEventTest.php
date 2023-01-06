<?php

namespace Tests\Unit\Models;

use App\Models\Travel;
use App\Models\TravelEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelEventTest extends TestCase
{
	use RefreshDatabase;

	public function testRelations() {
		$event = TravelEvent::factory()->create();
		$this->assertInstanceOf(Travel::class, $event->travel);
	}
}
