<?php

namespace Nowhere\AdminUtility;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
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
        $this->registerViews();
        $this->registerMiddleware();
        $this->registerRoutes();
    }

    public function register()
    {
        $this->app->bind(ConsoleCommandServiceInterface::class, ConsoleCommandService::class);
        $this->app->bind(CrudServiceInterface::class, CrudService::class); // done
        $this->app->bind(DbBackupServiceInterface::class, DbBackupService::class);
        $this->app->bind(EnvEditorServiceInterface::class, EnvEditorService::class); // done
        $this->app->bind(FileManagerInterface::class, FileManagerService::class); // done
        $this->app->bind(ImpersonationServiceInterface::class, ImpersonationService::class); // done
        $this->app->bind(MagicLoginServiceInterface::class, MagicLoginService::class); // done
        $this->app->bind(MaintenanceServiceInterface::class, MaintenanceService::class);  // done
        $this->app->bind(SystemInfoServiceInterface::class, SystemInfoService::class); // done
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
}
