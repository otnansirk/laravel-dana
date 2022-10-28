<?php

namespace Otnansirk\Dana;

use Illuminate\Support\ServiceProvider;
use Otnansirk\Dana\Services\DANAPayService;
use Otnansirk\Dana\Services\DANACoreService;

class DanaCoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('DANACore', DANACoreService::class);
        $this->app->bind('DANAPay', DANAPayService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/dana.php' => config_path('dana.php'),
        ]);
    }
}
