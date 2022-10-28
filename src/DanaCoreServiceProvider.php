<?php

namespace Otnansirk\Danacore;

use Illuminate\Foundation\AliasLoader;
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
        $this->app->register('Otnansirk\Danacore\DanaCoreServiceProvider');
        $this->app->bind('DANACore', DANACoreService::class);
        $this->app->bind('DANAPay', DANAPayService::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('DANA', \Otnansirk\Danacore\Facades\DANAPay::class);
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
