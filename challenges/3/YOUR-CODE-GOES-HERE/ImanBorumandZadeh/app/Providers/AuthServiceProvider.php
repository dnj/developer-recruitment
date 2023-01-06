<?php

namespace App\Providers;

use App\Models\Driver;
use App\Models\Travel;
use App\Models\TravelSpot;
use App\Policies\DriverPolicy;
use App\Policies\TravelPolicy;
use App\Policies\TravelSpotPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Travel::class => TravelPolicy::class,
        TravelSpot::class => TravelSpotPolicy::class,
        Driver::class => DriverPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
