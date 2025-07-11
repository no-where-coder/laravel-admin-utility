<?php

namespace Nowhere\AdminUtility;

use Illuminate\Support\ServiceProvider;
use Nowhere\AdminUtility\Http\Middleware\BypassMaintenance;

class AdminUtilityServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/Views', 'admin-utility');

        app('router')->aliasMiddleware('bypass.maintenance', BypassMaintenance::class);
    }

    public function register()
    {
        //
    }
}
