<?php

namespace Tanedaa\LaravelDynamicMaintenance\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class EnableMaintenanceForRoutes extends Command
{
    protected $signature = 'down:routes {routes*} {--secret=}';
    protected $description = 'Enable maintenance mode for specific routes';

    public function handle()
    {
        $newRoutes = $this->argument('routes');
        $existingRoutes = Cache::get('maintenance_routes', []);
        $updatedRoutes = array_unique(array_merge($existingRoutes, $newRoutes));

        Cache::forever('maintenance_routes', $updatedRoutes);

        $this->info('Maintenance mode enabled for routes: ' . implode(', ', $updatedRoutes));

        $secret = $this->option('secret');
        if ($secret) {
            Cache::forever('maintenance_bypass_secret', $secret);
            $this->info('Maintenance bypass secret set.');
        }
    }
}