<?php

namespace Nowhere\AdminUtility\Http\Middleware;

use Closure;

class SecurityHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        return $response->withHeaders([
            'X-Frame-Options' => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'no-referrer',
            'Strict-Transport-Security' => 'max-age=63072000; includeSubDomains; preload',
            'X-XSS-Protection' => '1; mode=block',
        ]);
    }
}
