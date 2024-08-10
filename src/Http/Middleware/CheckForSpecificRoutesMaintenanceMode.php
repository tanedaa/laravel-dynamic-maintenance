<?php

namespace Tanedaa\LaravelDynamicMaintenance\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CheckForSpecificRoutesMaintenanceMode
{
    public function handle($request, Closure $next)
    {
        $maintenanceRoutes = Cache::get('maintenance_routes', []);

        if (in_array($request->route()->getName(), $maintenanceRoutes)) {
            return response()->view('errors.my-503', [
                'title' => config('maintenance.title'),
                'message' => config('maintenance.message'),
                'code' => config('maintenance.code'),
            ], 503);
        }

        return $next($request);
    }
}
