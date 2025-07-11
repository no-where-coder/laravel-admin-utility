<?php

namespace Nowhere\AdminUtility\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Nowhere\AdminUtility\Contracts\EnvEditorServiceInterface;

class EnvEditorService implements EnvEditorServiceInterface
{
    protected string $envDirectory;

    public function __construct()
    {
        $this->envDirectory = base_path();
    }

    public function listEnvFiles(): array
    {
        return collect(glob($this->envDirectory . '/.env*'))
            ->map(fn($path) => basename($path))
            ->values()
            ->all();
    }

    public function readEnvFile(string $filename): string
    {
        $path = $this->resolvePath($filename);

        if (!File::exists($path)) {
            throw new \RuntimeException("File $filename not found.");
        }

        return File::get($path);
    }

    public function updateEnvFile(string $filename, string $content): void
    {
        $path = $this->resolvePath($filename);

        if (!File::exists($path)) {
            throw new \RuntimeException("File $filename not found.");
        }

        File::put($path, $content);

        if ($filename === '.env') {
            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }
    }

    protected function resolvePath(string $filename): string
    {
        $safe = basename($filename); // prevent directory traversal
        return base_path($safe);
    }
}
