<?php

namespace Nowhere\AdminUtility\Contracts;

use Illuminate\Http\UploadedFile;

interface FileManagerInterface
{
    public function listFiles(string $folder = ''): array;

    public function listFolders(string $folder = ''): array;

    public function uploadFile(UploadedFile $file, string $folder = ''): void;

    public function createFolder(string $folderPath): void;

    public function deleteFile(string $filePath): void;

    public function deleteFolder(string $folderPath): void;
}
