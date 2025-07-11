<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Nowhere\AdminUtility\Contracts\MagicLoginServiceInterface;

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
}
