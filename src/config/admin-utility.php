<?php

$dir = __DIR__;
$vendorIndex = strpos($dir, 'vendor/');
$relativeDir = $vendorIndex !== false ? substr($dir, $vendorIndex) : $dir;

return [
    'route_prefix' => env('APP_ADMIN_PATH_KEY', substr(md5($relativeDir), 0, 12)),
    'cookie_key'   => env('APP_ADMIN_COOKIE_KEY', 'admin_cookie'),
    'cookie_value' => env('APP_ADMIN_COOKIE_VAL', hash('sha256', 'default_' . $relativeDir)),
];
