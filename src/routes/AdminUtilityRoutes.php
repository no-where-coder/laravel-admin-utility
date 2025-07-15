<?php

namespace Nowhere\AdminUtility\Routes;

use Illuminate\Support\Facades\Route;
use Nowhere\AdminUtility\Contracts\RouteGroupInterface;
use Nowhere\AdminUtility\Http\Controllers\{
    CrudController,
    FileUploadController,
    CommandController,
    ImpersonationController,
    EnvEditorController,
    SystemInfoController,
    MaintenanceController,
    DbController,
    LoginController
};

class AdminUtilityRoutes implements RouteGroupInterface
{
    protected array $middleware = ['admin-web', 'admin-utility.secure-headers', 'admin-utility.secure-cookie'];

    public function register(): void
    {
        $prefix = config('admin-utility.route_prefix', 'admin-utility');
        // Route::get("/{$prefix}/cookies", [LoginController::class, 'setCookies'])->name('admin.handle-cookies');

        Route::middleware($this->middleware)->group(function () use ($prefix) {
            Route::get("/{$prefix}/login", [LoginController::class, 'magicLogin'])->name('admin.magic-login');

            Route::prefix($prefix)->group(function() {
                $this->registerCrudRoutes();
                $this->registerFileUploadRoutes();
                $this->registerArtisanRoutes();
                $this->registerImpersonationRoutes();
                $this->registerEnvEditorRoutes();
                $this->registerSystemInfoRoutes();
                $this->registerMaintenanceRoutes();
                $this->registerDbRoutes();
            });
        });
    }

    protected function registerCrudRoutes(): void
    {
        Route::get('/crud', [CrudController::class, 'index'])->name('admin.crud.index');
        Route::get('/crud/{table}', [CrudController::class, 'records'])->name('admin.crud.records');
        Route::get('/crud/{table}/create', [CrudController::class, 'create'])->name('admin.crud.create');
        Route::post('/crud/{table}', [CrudController::class, 'store'])->name('admin.crud.store');
        Route::get('/crud/{table}/{id}/edit', [CrudController::class, 'edit'])->name('admin.crud.edit');
        Route::put('/crud/{table}/{id}', [CrudController::class, 'update'])->name('admin.crud.update');
        Route::delete('/crud/{table}/{id}', [CrudController::class, 'destroy'])->name('admin.crud.destroy');
    }

    protected function registerFileUploadRoutes(): void
    {
        Route::get('/upload', [FileUploadController::class, 'index'])->name('admin.upload.index');
        Route::post('/upload', [FileUploadController::class, 'store'])->name('admin.upload.store');
        Route::post('/upload/create-folder', [FileUploadController::class, 'createFolder'])->name('admin.upload.create-folder');
        Route::delete('/upload/delete-file', [FileUploadController::class, 'deleteFile'])->name('admin.upload.delete-file');
        Route::delete('/upload/delete-folder', [FileUploadController::class, 'deleteFolder'])->name('admin.upload.delete-folder');
    }

    protected function registerArtisanRoutes(): void
    {
        Route::get('/artisan', [CommandController::class, 'index'])->name('admin.artisan.index');
        Route::post('/artisan', [CommandController::class, 'run'])->name('admin.artisan.run');
    }

    protected function registerImpersonationRoutes(): void
    {
        Route::get('/impersonate/{id}', [ImpersonationController::class, 'impersonate'])->name('admin.impersonate');
        Route::get('/impersonate/stop', [ImpersonationController::class, 'stop'])->name('admin.impersonate.stop');
    }

    protected function registerEnvEditorRoutes(): void
    {
        Route::get('/env', [EnvEditorController::class, 'index'])->name('admin.env.index');
        Route::get('/env/edit/{filename}', [EnvEditorController::class, 'edit'])->name('admin.env.edit');
        Route::post('/env/update/{filename}', [EnvEditorController::class, 'update'])->name('admin.env.update');
    }

    protected function registerSystemInfoRoutes(): void
    {
        Route::get('/system-info', [SystemInfoController::class, 'index'])->name('admin.system.index');
    }

    protected function registerMaintenanceRoutes(): void
    {
        Route::middleware(['bypass.maintenance'])->group(function () {
            Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('admin.maintenance.index');
            Route::post('/maintenance/toggle', [MaintenanceController::class, 'toggle'])->name('admin.maintenance.toggle');
        });
    }

    protected function registerDbRoutes(): void
    {
        Route::get('/db', [DbController::class, 'index'])->name('admin.db.index');
        Route::post('/db/backup', [DbController::class, 'backup'])->name('admin.db.backup');
        Route::post('/db/restore', [DbController::class, 'restore'])->name('admin.db.restore');
    }
}
