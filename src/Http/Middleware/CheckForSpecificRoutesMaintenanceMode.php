<?php

namespace Tanedaa\LaravelDynamicMaintenance\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

class CheckForSpecificRoutesMaintenanceMode
{
    public function handle($request, Closure $next)
    {
        $maintenanceRoutes = Cache::get('maintenance_routes', []);
        $bypassSecret = Cache::get('maintenance_bypass_secret');

        if ($bypassSecret && $request->query('secret') === $bypassSecret) {
            Cookie::queue('maintenance_bypass', $bypassSecret, 120);
            return redirect($request->url());
        }

        if (in_array($request->route()->getName(), $maintenanceRoutes)) {
            if ($bypassSecret && $request->cookie('maintenance_bypass') === $bypassSecret) {
                return $next($request);
            }

            return response()->view('errors.my-503', [
                'title' => config('maintenance.title'),
                'message' => config('maintenance.message'),
                'code' => config('maintenance.code'),
            ], 503);
        }

        return $next($request);
    }
}
