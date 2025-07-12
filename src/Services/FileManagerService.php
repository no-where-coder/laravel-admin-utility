<?php

namespace Nowhere\AdminUtility\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Nowhere\AdminUtility\Contracts\FileManagerInterface;
use Nowhere\AdminUtility\Services\AdminLogger;

class FileManagerService implements FileManagerInterface
{
    protected string $basePath;
    protected AdminLogger $logger;

    public function __construct(AdminLogger $logger)
    {
        $this->basePath = public_path();
        $this->logger = $logger;
    }

    public function listFiles(string $folder = ''): array
    {
        try {
            $path = $this->resolvePath($folder);
            return File::files($path);
        } catch (\Throwable $e) {
            $this->logger->error("FileManagerService listFiles error: " . $e->getMessage());
            return [];
        }
    }

    public function listFolders(string $folder = ''): array
    {
        try {
            $path = $this->resolvePath($folder);
            return File::directories($path);
        } catch (\Throwable $e) {
            $this->logger->error("FileManagerService listFolders error: " . $e->getMessage());
            return [];
        }
    }

    public function uploadFile(UploadedFile $file, string $folder = ''): void
    {
        try {
            $destination = $this->resolvePath($folder);

            if (!is_dir($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $file->move($destination, $file->getClientOriginalName());
        } catch (\Throwable $e) {
            $this->logger->error("FileManagerService uploadFile error: " . $e->getMessage());
            throw $e;
        }
    }

    public function createFolder(string $folderPath): void
    {
        try {
            $path = $this->resolvePath($folderPath);

            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
        } catch (\Throwable $e) {
            $this->logger->error("FileManagerService createFolder error: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteFile(string $filePath): void
    {
        try {
            $path = $this->resolvePath($filePath);

            if (File::exists($path)) {
                File::delete($path);
            }
        } catch (\Throwable $e) {
            $this->logger->error("FileManagerService deleteFile error: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteFolder(string $folderPath): void
    {
        try {
            $path = $this->resolvePath($folderPath);

            if (File::isDirectory($path)) {
                File::deleteDirectory($path);
            }
        } catch (\Throwable $e) {
            $this->logger->error("FileManagerService deleteFolder error: " . $e->getMessage());
            throw $e;
        }
    }

    protected function resolvePath(string $relative): string
    {
        return rtrim($this->basePath, '/') . '/' . ltrim(trim($relative, '/'), '/');
    }
}
