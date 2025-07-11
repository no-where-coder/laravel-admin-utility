<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class SystemInfoController
{
    public function index()
    {
        $info = [
            'Laravel Version' => app()->version(),
            'PHP Version' => phpversion(),
            'Database Connection' => config('database.default'),
            'DB Version' => DB::select("select version() as version")[0]->version ?? 'N/A',
            'App Environment' => config('app.env'),
            'App Debug' => config('app.debug') ? 'true' : 'false',
            'App URL' => config('app.url'),
            'Timezone' => config('app.timezone'),
            'Locale' => config('app.locale'),
            'Cache Driver' => config('cache.default'),
            'Session Driver' => config('session.driver'),
            'Queue Driver' => config('queue.default'),
            'Storage Path' => storage_path(),
            'Public Path' => public_path(),
            'Base Path' => base_path(),
            'Available Disk Space' => $this->getDiskSpace()
        ];

        return view('admin-utility::system.index', compact('info'));
    }

    private function getDiskSpace(): string
    {
        $bytes = disk_free_space('/');
        return $this->formatBytes($bytes);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / (1024 ** $pow), $precision) . ' ' . $units[$pow];
    }
}
