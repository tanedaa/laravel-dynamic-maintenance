<?php

namespace Tanedaa\LaravelDynamicMaintenance;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Tanedaa\LaravelDynamicMaintenance\Commands\EnableMaintenanceForRoutes;
use Tanedaa\LaravelDynamicMaintenance\Commands\DisableMaintenanceForRoutes;
use Tanedaa\LaravelDynamicMaintenance\Http\Middleware\CheckForSpecificRoutesMaintenanceMode;

class ServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/maintenance.php', 'maintenance'
        );

        $this->commands([
            EnableMaintenanceForRoutes::class,
            DisableMaintenanceForRoutes::class,
        ]);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/maintenance.php' => config_path('maintenance.php'),
            __DIR__ . '/resources/views/errors/my-503.blade.php' => resource_path('views/errors/my-503.blade.php'),
        ], 'laravel-dynamic-maintenance');

        $this->app['router']->aliasMiddleware('maintenance', CheckForSpecificRoutesMaintenanceMode::class);
    }
}
