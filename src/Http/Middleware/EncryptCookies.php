<?php

namespace Nowhere\AdminUtility\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use Illuminate\Contracts\Encryption\Encrypter;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [];

    public function __construct(Encrypter $encrypter)
    {
        parent::__construct($encrypter);

        $this->except = [
            config('admin-utility.cookie_key', 'admin_cookie'),
            // Add other cookie names if needed
        ];
    }
}
