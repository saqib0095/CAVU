<?php

namespace App\Providers;

use App\Services\ParkingService;
use Illuminate\Support\ServiceProvider;

class ParkingProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ParkingService::class, function ($app) {
            return new ParkingService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
