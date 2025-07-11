<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LoginController
{
    public function magicLogin(Request $request)
    {
        $email = $request->query('email');

        $user = $email
            ? User::where('email', $email)->first()
            : User::first();


        if (! $user) {
            return response('User not found in users table.', 404);
        }

        // Log in using user ID
        Auth::loginUsingId($user->id);

        if (method_exists($request, 'session') && $request->hasSession()) {
            $request->session()->regenerate();
        }

        return Redirect::to('/')->with('success', "Logged in as {$user->email}");
    }
}
