<?php

namespace Nowhere\AdminUtility\Contracts;

interface ConsoleCommandServiceInterface
{
    public function predefinedCommands(): array;

    public function execute(string $command): string;

    public function isSafeCommand(string $command): bool;
}