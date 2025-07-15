<?php

namespace Nowhere\AdminUtility\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BypassMaintenance
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->app->isDownForMaintenance()) {
            return $this->shouldBypass($request)
                ? $next($request)
                : $this->maintenanceResponse();
        }

        return $next($request);
    }

    protected function shouldBypass(Request $request): bool
    {
        return str_starts_with($request->path(), 'admin-utility');
    }

    protected function maintenanceResponse(): never
    {
        throw new HttpException(503, 'Service Unavailable (Maintenance Mode)');
    }
}
