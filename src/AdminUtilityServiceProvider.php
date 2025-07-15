<?php

namespace Nowhere\AdminUtility;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
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
use Nowhere\AdminUtility\Http\Middleware\SecurityHeadersMiddleware;
use Nowhere\AdminUtility\Http\Middleware\SecureAdminAccessMiddleware;
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
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerViews();
        $this->registerMiddleware();

        $this->registerPublishables();
        $this->autoPublishAssets();
    }

    public function register(): void
    {
        // Merge package config (from src/config) so config('admin-utility.*') works
        $this->mergeConfigFrom(__DIR__ . '/config/admin-utility.php', 'admin-utility');

        // Bind your services
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


    protected function registerRoutes(): void
    {
        if (! $this->app->routesAreCached()) {
            (new AdminUtilityRoutes())->register();
        }
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/Views', 'admin-utility');
    }

    protected function registerMiddleware(): void
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);

        $router->aliasMiddleware('bypass.maintenance', BypassMaintenance::class);
        $router->aliasMiddleware('admin-utility.secure-headers', SecurityHeadersMiddleware::class);
        $router->aliasMiddleware('admin-utility.secure-cookie', SecureAdminAccessMiddleware::class);

        // Define a custom middleware group similar to 'web'
        $router->middlewareGroup('admin-web', [
            \Nowhere\AdminUtility\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    }

    /**
     * Publish assets, views, and config.
     */
    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__ . '/assets' => public_path('vendor/admin-utility/assets'),
        ], 'admin-utility-assets');

        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/vendor/admin-utility'),
        ], 'admin-utility-views');

        $this->publishes([
            __DIR__ . '/config/admin-utility.php' => config_path('admin-utility.php'),
        ], 'admin-utility-config');
    }

    protected function autoPublishAssets(): void
    {
        $source = __DIR__ . '/assets';
        $destination = public_path('vendor/admin-utility/assets');

        if (! File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
            File::copyDirectory($source, $destination);
        }
    }
}
