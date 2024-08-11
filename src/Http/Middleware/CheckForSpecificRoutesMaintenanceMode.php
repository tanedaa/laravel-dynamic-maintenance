<?php

namespace Tanedaa\LaravelDynamicMaintenance\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

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

        $currentRouteName = $request->route()->getName();
        $currentRoutePath = ltrim($request->path(), '/');

        foreach ($maintenanceRoutes as $route) {
            if ($this->matchesRoute($route, $currentRouteName, $currentRoutePath)) {
                if ($bypassSecret && $request->cookie('maintenance_bypass') === $bypassSecret) {
                    return $next($request);
                }

                return response()->view('errors.my-503', [
                    'title' => config('maintenance.title'),
                    'message' => config('maintenance.message'),
                    'code' => config('maintenance.code'),
                ], 503);
            }
        }

        return $next($request);
    }

    private function matchesRoute($pattern, $routeName, $routePath)
    {
        $normalizedPattern = ltrim($pattern, '/');
        return Str::is($normalizedPattern, $routeName) || Str::is($normalizedPattern, $routePath);
    }
}
