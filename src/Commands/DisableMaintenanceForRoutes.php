<?php

namespace Tanedaa\LaravelDynamicMaintenance\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class DisableMaintenanceForRoutes extends Command
{
    protected $signature = 'up:routes {routes*}';
    protected $description = 'Disable maintenance mode for specific routes';

    public function handle()
    {
        $disableRoutes = $this->argument('routes');

        if ($disableRoutes === ['all']) {
            Cache::forget('maintenance_routes');
            $this->info('Maintenance mode disabled for all routes');
            return;
        }

        $existingRoutes = Cache::get('maintenance_routes', []);

        $updatedRoutes = array_diff($existingRoutes, $disableRoutes);

        Cache::forever('maintenance_routes', $updatedRoutes);

        $this->info('Maintenance mode disabled for routes: ' . implode(', ', $disableRoutes));
    }
}
