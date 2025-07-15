<?php

namespace Nowhere\AdminUtility\Setup;

class EnvConfigurator
{
    public static function injectSecureEnv(): void
    {
        // Determine the .env file path safely
        $envPath = function_exists('base_path')
            ? base_path('.env')
            : dirname(__DIR__, 3) . '/.env'; // Adjust depending on directory depth

        // Exit early if .env file does not exist
        if (!file_exists($envPath)) {
            return;
        }

        // Read current .env content into an array of lines
        $envContent = file($envPath);

        // Generate random key
        $rand = substr(md5(uniqid('', true)), 0, 12);

        // Prepare new environment lines
        $lines = [
            "APP_ADMIN_PATH_KEY=admin-utility-{$rand}",
            "APP_ADMIN_COOKIE_KEY=admin_cookie",
            "APP_ADMIN_COOKIE_VAL=" . hash('sha256', $rand),
        ];

        // Insert the new lines after the 12th line (or at end if shorter)
        $insertIndex = min(12, count($envContent));
        array_splice($envContent, $insertIndex, 0, array_map(fn($line) => $line . PHP_EOL, $lines));

        // Write the updated content back to the .env file
        file_put_contents($envPath, implode('', $envContent));
    }
}
