<?php

namespace Otnansirk\Danacore;

use Illuminate\Support\ServiceProvider;
use Otnansirk\Danacore\Services\DANAPayService;
use Otnansirk\Danacore\Services\DANACoreService;

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
