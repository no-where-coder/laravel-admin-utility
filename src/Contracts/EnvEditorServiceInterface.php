<?php

namespace Nowhere\AdminUtility\Contracts;

interface EnvEditorServiceInterface
{
    public function listEnvFiles(): array;

    public function readEnvFile(string $filename): string;

    public function updateEnvFile(string $filename, string $content): void;
}
