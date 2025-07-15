<?php

namespace Nowhere\AdminUtility\Services;

use Illuminate\Support\Facades\Artisan;
use Nowhere\AdminUtility\Contracts\MaintenanceServiceInterface;
use Nowhere\AdminUtility\Services\AdminLogger;

class MaintenanceService implements MaintenanceServiceInterface
{
    protected string $secret = 'admin-utility-secret';
    protected AdminLogger $logger;

    public function __construct(AdminLogger $logger)
    {
        $this->logger = $logger;
    }

    public function isDown(): bool
    {
        try {
            return app()->isDownForMaintenance();
        } catch (\Throwable $e) {
            $this->logger->error("MaintenanceService isDown error: " . $e->getMessage());
            return false;
        }
    }

    public function enable(): void
    {
        try {
            Artisan::call('down', [
                '--secret' => $this->secret
            ]);
        } catch (\Throwable $e) {
            $this->logger->error("MaintenanceService enable error: " . $e->getMessage());
            throw $e;
        }
    }

    public function disable(): void
    {
        try {
            Artisan::call('up');
        } catch (\Throwable $e) {
            $this->logger->error("MaintenanceService disable error: " . $e->getMessage());
            throw $e;
        }
    }
}
