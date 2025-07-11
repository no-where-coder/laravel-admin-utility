<?php

namespace Nowhere\AdminUtility\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Request;

class BypassMaintenance
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->isDownForMaintenance()) {
            // Allow admin utility routes during maintenance
            if (str_starts_with($request->path(), 'admin-utility')) {
                return $next($request);
            }

            // Otherwise, show default maintenance response
            throw new MaintenanceModeException(
                now(), null, 'Maintenance Mode'
            );
        }

        return $next($request);
    }
}
