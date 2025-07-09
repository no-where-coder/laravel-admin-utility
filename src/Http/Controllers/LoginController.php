<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class LoginController
{
    public function magicLogin(Request $request)
    {
        $email = $request->query('email');

        // Get user: by email or first user
        $user = $email
            ? User::where('email', $email)->first()
            : User::first();

        if (! $user) {
            return response('User not found', 404);
        }

        Auth::login($user);

        return Redirect::to('/')->with('success', 'Logged in as '.$user->email);
    }
}