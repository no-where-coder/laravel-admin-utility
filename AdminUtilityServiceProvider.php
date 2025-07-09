<?php

namespace Nowhere\AdminUtility;

use Illuminate\Support\ServiceProvider;

class AdminUtilityServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/Views', 'admin-utility');
    }

    public function register()
    {
        //
    }
}
