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
            Cache::forget('maintenance_bypass_secret');
            $this->info('Maintenance mode disabled for all routes');
            return;
        }

        $existingRoutes = Cache::get('maintenance_routes', []);

        $updatedRoutes = array_diff($existingRoutes, $disableRoutes);

        Cache::forever('maintenance_routes', $updatedRoutes);

        if (empty($updatedRoutes)) {
            $secret = Cache::get('maintenance_bypass_secret');
            if ($secret) {
                Cache::forget('maintenance_bypass_secret');
                $this->info('Bypass secret removed as no routes are in maintenance mode.');
            }
        }

        $this->info('Maintenance mode disabled for routes: ' . implode(', ', $disableRoutes));
    }
}