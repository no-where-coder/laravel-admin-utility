<?php

return [
    'route_prefix'   => env('APP_ADMIN_PATH_KEY', 'admin-utility-' . substr(md5(__DIR__), 0, 12)),
    'cookie_key'     => env('APP_ADMIN_COOKIE_KEY', 'admin_cookie'),
    'cookie_value'   => env('APP_ADMIN_COOKIE_VAL', hash('sha256', 'default_' . __DIR__)),
];
