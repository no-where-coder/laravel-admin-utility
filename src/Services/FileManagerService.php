<?php

namespace Nowhere\AdminUtility\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Nowhere\AdminUtility\Contracts\FileManagerInterface;

class FileManagerService implements FileManagerInterface
{
    protected string $basePath;

    public function __construct()
    {
        $this->basePath = public_path();
    }

    public function listFiles(string $folder = ''): array
    {
        $path = $this->resolvePath($folder);
        return File::files($path);
    }

    public function listFolders(string $folder = ''): array
    {
        $path = $this->resolvePath($folder);
        return File::directories($path);
    }

    public function uploadFile(UploadedFile $file, string $folder = ''): void
    {
        $destination = $this->resolvePath($folder);

        if (!is_dir($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $file->move($destination, $file->getClientOriginalName());
    }

    public function createFolder(string $folderPath): void
    {
        $path = $this->resolvePath($folderPath);

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    public function deleteFile(string $filePath): void
    {
        $path = $this->resolvePath($filePath);

        if (File::exists($path)) {
            File::delete($path);
        }
    }

    public function deleteFolder(string $folderPath): void
    {
        $path = $this->resolvePath($folderPath);

        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
        }
    }

    protected function resolvePath(string $relative): string
    {
        return rtrim($this->basePath, '/') . '/' . ltrim(trim($relative, '/'), '/');
    }
}
