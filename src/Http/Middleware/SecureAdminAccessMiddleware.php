<?php

namespace Nowhere\AdminUtility\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureAdminAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $cookieName = config('admin-utility.cookie_key');
        $expectedValue = config('admin-utility.cookie_value');
        $cookieValue = $request->cookie($cookieName);

        if ($cookieValue !== $expectedValue) {
            abort(404, 'Page not found.');
        }

        return $next($request);
    }
}
