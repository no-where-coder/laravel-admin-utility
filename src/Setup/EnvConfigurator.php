<?php

namespace Nowhere\AdminUtility\Setup;

class EnvConfigurator
{
    public static function injectSecureEnv(): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) return;

        $envContent = file($envPath);

        // Generate random key & value
        $rand = substr(md5(uniqid()), 0, 12);
        $lines = [
            "APP_ADMIN_PATH_KEY=admin-utility-{$rand}",
            "APP_ADMIN_COOKIE_KEY=admin_cookie",
            "APP_ADMIN_COOKIE_VAL=" . hash('sha256', $rand),
        ];

        array_splice($envContent, 12, 0, array_map(fn($l) => "$l\n", $lines));
        file_put_contents($envPath, implode('', $envContent));
    }
}
