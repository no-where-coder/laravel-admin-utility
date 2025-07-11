<?php

namespace Nowhere\AdminUtility\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Nowhere\AdminUtility\Contracts\MagicLoginServiceInterface;

class MagicLoginService implements MagicLoginServiceInterface
{
    public function login(Request $request): User
    {
        $email = $request->query('email');

        $user = $email
            ? User::where('email', $email)->first()
            : User::first();

        if (! $user) {
            throw new \RuntimeException('User not found in users table.');
        }

        Auth::loginUsingId($user->id);

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return $user;
    }
}
