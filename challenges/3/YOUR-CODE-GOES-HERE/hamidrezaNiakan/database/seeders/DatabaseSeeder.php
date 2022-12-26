<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelEvent;
use App\Models\TravelSpot;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		\App\Models\User::factory(10)->create();
		Driver::factory(10)->create();
		Travel::factory(3)->create();
		Travel::factory(2)->searchingForDriver()->create();
		TravelSpot::factory(9)->create();
		TravelEvent::factory(9)->create();
    }
}
