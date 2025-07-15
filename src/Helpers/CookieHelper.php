<?php

namespace Nowhere\AdminUtility\Helpers;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;

class CookieHelper
{
    public static function generateCookie(
        string $name,
        string $value,
        int $minutes = 2,
        bool $secure = false,
        string $sameSite = 'Lax',
        bool $httpOnly = true,
        string $path = '/',
        ?string $domain = null
    ): Cookie {
        $expiresAt = Carbon::now()->addMinutes($minutes);
    
        return new Cookie(
            name: $name,
            value: $value,
            expire: $expiresAt->getTimestamp(),
            path: $path,
            domain: $domain,
            secure: $secure,
            httpOnly: $httpOnly,
            raw: false,
            sameSite: $sameSite
        );
    }
    
}
