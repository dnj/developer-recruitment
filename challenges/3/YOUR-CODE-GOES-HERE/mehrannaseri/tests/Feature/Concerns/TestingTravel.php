<?php

namespace Tests\Feature\Concerns;

use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelEvent;
use App\Models\TravelSpot;
use App\Models\User;

trait TestingTravel {

	protected function runningTravel(User $passenger, Driver $driver, bool $arrivedOrigin = true, bool $arrivedDestination = false)
	{
		$origin = TravelSpot::factory()->withPosition(0);
		if ($arrivedOrigin) {
			$origin = $origin->arrived();
		}

		$dest = TravelSpot::factory()->withPosition(1);
		if ($arrivedDestination) {
			$dest = $dest->arrived();
		}
		return Travel::factory()
			->withPassenger($passenger)
			->withDriver($driver)
			->running()
			->has($origin, 'spots')
			->has($dest, 'spots')
			->has(TravelEvent::factory()->acceptByDriver(), 'events');
	}

	/**
	 * @return [User,Driver]
	 */
	protected function createPassengerDriver(): array
	{
		$passenger = User::factory()->create();
		$driver = Driver::factory()->create();

		return [$passenger, $driver];
	}
}