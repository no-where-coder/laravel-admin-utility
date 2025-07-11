<?php

namespace Nowhere\AdminUtility\Services;

use Illuminate\Support\Facades\Artisan;
use Nowhere\AdminUtility\Contracts\MaintenanceServiceInterface;

class MaintenanceService implements MaintenanceServiceInterface
{
    protected string $secret = 'admin-utility-secret';

    public function isDown(): bool
    {
        return app()->isDownForMaintenance();
    }

    public function enable(): void
    {
        Artisan::call('down', [
            '--secret' => $this->secret
        ]);
    }

    public function disable(): void
    {
        Artisan::call('up');
    }
}
