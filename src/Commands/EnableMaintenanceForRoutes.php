<?php

namespace Tanedaa\LaravelDynamicMaintenance\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class EnableMaintenanceForRoutes extends Command
{
    protected $signature = 'down:routes {routes?*} {--secret=}';
    protected $description = 'Enable maintenance mode for specific routes';

    public function handle()
    {
        $newRoutes = $this->argument('routes');

        if (empty($newRoutes)) {
            $this->displayHelp();
            return;
        }

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

    protected function displayHelp()
    {
        $this->info("Usage: php artisan down:routes {routes*} {--secret=}");
        $this->info("Enables maintenance mode for specific routes.");
        $this->info("Options:");
        $this->info("    --secret=SECRET       Set a secret key to bypass maintenance mode.");
        $this->info("Examples:");
        $this->info("    php artisan down:routes home");
        $this->info("    php artisan down:routes route1 route2 --secret=mySecretKey");
    }
}
