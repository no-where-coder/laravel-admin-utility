<?php

namespace Nowhere\AdminUtility\Services;

use Illuminate\Support\Facades\DB;
use Nowhere\AdminUtility\Contracts\SystemInfoServiceInterface;
use Nowhere\AdminUtility\Services\AdminLogger;

class SystemInfoService implements SystemInfoServiceInterface
{
    protected AdminLogger $logger;

    public function __construct(AdminLogger $logger)
    {
        $this->logger = $logger;
    }

    public function getSystemInfo(): array
    {
        try {
            return [
                'Laravel Version'       => app()->version(),
                'PHP Version'           => phpversion(),
                'Database Connection'   => config('database.default'),
                'DB Version'            => DB::select("SELECT version() as version")[0]->version ?? 'N/A',
                'App Environment'       => config('app.env'),
                'App Debug'             => config('app.debug') ? 'true' : 'false',
                'App URL'               => config('app.url'),
                'Timezone'              => config('app.timezone'),
                'Locale'                => config('app.locale'),
                'Cache Driver'          => config('cache.default'),
                'Session Driver'        => config('session.driver'),
                'Queue Driver'          => config('queue.default'),
                'Storage Path'          => storage_path(),
                'Public Path'           => public_path(),
                'Base Path'             => base_path(),
                'Available Disk Space'  => $this->getDiskSpace()
            ];
        } catch (\Throwable $e) {
            $this->logger->error("SystemInfoService getSystemInfo error: " . $e->getMessage());
            return [];
        }
    }

    private function getDiskSpace(): string
    {
        try {
            $bytes = disk_free_space('/');
            return $this->formatBytes($bytes);
        } catch (\Throwable $e) {
            $this->logger->error("SystemInfoService getDiskSpace error: " . $e->getMessage());
            return 'N/A';
        }
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);

        return round($bytes / (1024 ** $pow), $precision) . ' ' . $units[$pow];
    }
}
