<?php

namespace Nowhere\AdminUtility;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Nowhere\AdminUtility\Contracts\ConsoleCommandServiceInterface;
use Nowhere\AdminUtility\Contracts\CrudServiceInterface;
use Nowhere\AdminUtility\Contracts\DbBackupServiceInterface;
use Nowhere\AdminUtility\Contracts\EnvEditorServiceInterface;
use Nowhere\AdminUtility\Contracts\FileManagerInterface;
use Nowhere\AdminUtility\Contracts\ImpersonationServiceInterface;
use Nowhere\AdminUtility\Contracts\MagicLoginServiceInterface;
use Nowhere\AdminUtility\Contracts\MaintenanceServiceInterface;
use Nowhere\AdminUtility\Contracts\SystemInfoServiceInterface;
use Nowhere\AdminUtility\Http\Middleware\BypassMaintenance;
use Nowhere\AdminUtility\Routes\AdminUtilityRoutes;
use Nowhere\AdminUtility\Services\ConsoleCommandService;
use Nowhere\AdminUtility\Services\CrudService;
use Nowhere\AdminUtility\Services\DbBackupService;
use Nowhere\AdminUtility\Services\EnvEditorService;
use Nowhere\AdminUtility\Services\FileManagerService;
use Nowhere\AdminUtility\Services\ImpersonationService;
use Nowhere\AdminUtility\Services\MagicLoginService;
use Nowhere\AdminUtility\Services\MaintenanceService;
use Nowhere\AdminUtility\Services\SystemInfoService;

class AdminUtilityServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Only register dynamically if explicitly accessed
        if ($this->shouldBootUtility()) {
            $this->registerRoutes();
            $this->registerViews();
            $this->registerMiddleware();
        }

        $this->registerPublishables();
        $this->autoPublishAssets();
    }

    public function register()
    {
        if ($this->shouldBootUtility()) {
            $this->app->bind(ConsoleCommandServiceInterface::class, ConsoleCommandService::class);
            $this->app->bind(CrudServiceInterface::class, CrudService::class);
            $this->app->bind(DbBackupServiceInterface::class, DbBackupService::class);
            $this->app->bind(EnvEditorServiceInterface::class, EnvEditorService::class);
            $this->app->bind(FileManagerInterface::class, FileManagerService::class);
            $this->app->bind(ImpersonationServiceInterface::class, ImpersonationService::class);
            $this->app->bind(MagicLoginServiceInterface::class, MagicLoginService::class);
            $this->app->bind(MaintenanceServiceInterface::class, MaintenanceService::class);
            $this->app->bind(SystemInfoServiceInterface::class, SystemInfoService::class);
        }
    }

    protected function shouldBootUtility(): bool
    {
        // Prevent booting via CLI like route:list, view:list, etc.
        if ($this->app->runningInConsole()) {
            return false;
        }

        // Optional condition — only load if request matches certain prefix
        $request = request();

        return str_starts_with($request->path(), 'admin-utility') ||
               $request->is('admin-utility/*');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'admin-utility');
    }

    protected function registerMiddleware(): void
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('bypass.maintenance', BypassMaintenance::class);
    }

    protected function registerRoutes(): void
    {
        if (!app()->routesAreCached()) {
            (new AdminUtilityRoutes())->register();
        }
    }

    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__ . '/assets' => public_path('vendor/admin-utility/assets'),
        ], 'admin-utility-assets');

        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/vendor/admin-utility'),
        ], 'admin-utility-views');
    }

    protected function autoPublishAssets(): void
    {
        $source = __DIR__ . '/assets';
        $destination = public_path('vendor/admin-utility/assets');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        File::copyDirectory($source, $destination);
    }
}
