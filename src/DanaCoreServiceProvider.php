<?php

namespace Otnansirk\Dana;

use Illuminate\Support\ServiceProvider;
use Otnansirk\Dana\Helpers\Calculation;
use Otnansirk\Dana\Services\DANAPayService;
use Otnansirk\Dana\Services\DANACoreService;

class DanaCoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Use singleton for better performance across all Laravel versions
        $this->app->singleton('DANACore', DANACoreService::class);
        $this->app->singleton('DANAPay', DANAPayService::class);
        $this->app->singleton('DANACalculation', Calculation::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../config/dana.php' => config_path('dana.php'),
        ], 'dana-config');

        // Load configuration if not already loaded
        if (!$this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/dana.php', 'dana');
        }
    }
}