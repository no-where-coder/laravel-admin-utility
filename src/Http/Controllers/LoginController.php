<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Nowhere\AdminUtility\Contracts\MagicLoginServiceInterface;
use Nowhere\AdminUtility\Helpers\CookieHelper;

class LoginController
{
    public function __construct(
        protected MagicLoginServiceInterface $loginService
    ) {}

    public function magicLogin(Request $request)
    {
        try {
            $user = $this->loginService->login($request);
        } catch (\Throwable $e) {
            return response($e->getMessage(), 404);
        }

        return Redirect::to('/')->with('success', "Logged in as {$user->email}");
    }

    public function setCookies(Request $request)
    {
        return response('Cookie Set')->withCookie(
            CookieHelper::generateCookie(
                config('admin-utility.cookie_key'),
                config('admin-utility.cookie_value'),
                2,       // minutes
                false,   // secure = false for dev
                'Lax'
            )
        );
    }
}
