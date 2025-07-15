<?php

namespace Nowhere\AdminUtility\Services;

use Illuminate\Support\Facades\Artisan;
use Nowhere\AdminUtility\Contracts\ConsoleCommandServiceInterface;
use Nowhere\AdminUtility\Services\AdminLogger;

class ConsoleCommandService implements ConsoleCommandServiceInterface
{
    protected const PREDEFINED_COMMANDS = [
        'cache:clear',
        'route:clear',
        'config:clear',
        'view:clear',
        'migrate',
        'route:list',
        'optimize:clear',
        'queue:restart',
        'schedule:run',
    ];

    protected const DANGEROUS_KEYWORDS = [
        'rm', 'drop', 'wipe', 'fresh', '--force',
    ];

    protected AdminLogger $logger;

    public function __construct(AdminLogger $logger)
    {
        $this->logger = $logger;
    }

    public function predefinedCommands(): array
    {
        return self::PREDEFINED_COMMANDS;
    }

    public function isSafeCommand(string $command): bool
    {
        foreach (self::DANGEROUS_KEYWORDS as $keyword) {
            if (str_contains($command, $keyword)) {
                return false;
            }
        }
        return true;
    }

    public function execute(string $command): string
    {
        try {
            Artisan::call($command);
            return Artisan::output();
        } catch (\Throwable $e) {
            $this->logger->error("ConsoleCommandService execute error: " . $e->getMessage());
            throw new \RuntimeException("Failed to execute command.");
        }
    }
}

