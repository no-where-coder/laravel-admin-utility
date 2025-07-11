<?php

namespace Nowhere\AdminUtility\Contracts;

use Illuminate\Http\UploadedFile;

interface DbBackupServiceInterface
{
    public function generateBackup(): string;

    public function restoreBackup(UploadedFile $file): void;
}
