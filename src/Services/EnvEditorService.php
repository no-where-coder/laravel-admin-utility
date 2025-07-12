<?php

namespace Nowhere\AdminUtility\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Nowhere\AdminUtility\Contracts\EnvEditorServiceInterface;
use Nowhere\AdminUtility\Services\AdminLogger;

class EnvEditorService implements EnvEditorServiceInterface
{
    protected string $envDirectory;
    protected AdminLogger $logger;

    public function __construct(AdminLogger $logger)
    {
        $this->envDirectory = base_path();
        $this->logger = $logger;
    }

    public function listEnvFiles(): array
    {
        try {
            return collect(glob($this->envDirectory . '/.env*'))
                ->map(fn($path) => basename($path))
                ->values()
                ->all();
        } catch (\Throwable $e) {
            $this->logger->error("EnvEditorService listEnvFiles error: " . $e->getMessage());
            return [];
        }
    }

    public function readEnvFile(string $filename): string
    {
        try {
            $path = $this->resolvePath($filename);

            if (!File::exists($path)) {
                throw new \RuntimeException("File $filename not found.");
            }

            return File::get($path);
        } catch (\Throwable $e) {
            $this->logger->error("EnvEditorService readEnvFile error: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateEnvFile(string $filename, string $content): void
    {
        try {
            $path = $this->resolvePath($filename);

            if (!File::exists($path)) {
                throw new \RuntimeException("File $filename not found.");
            }

            File::put($path, $content);

            if ($filename === '.env') {
                Artisan::call('config:clear');
                Artisan::call('config:cache');
            }
        } catch (\Throwable $e) {
            $this->logger->error("EnvEditorService updateEnvFile error: " . $e->getMessage());
            throw $e;
        }
    }

    protected function resolvePath(string $filename): string
    {
        $safe = basename($filename); // prevent directory traversal
        return base_path($safe);
    }
}
