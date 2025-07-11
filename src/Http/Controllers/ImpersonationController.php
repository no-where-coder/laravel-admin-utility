<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class ImpersonationController
{
    public function impersonate(Request $request, $id)
    {
        $target = User::findOrFail($id);

        // Store original user ID
        session(['impersonate_original_id' => auth()->id()]);

        // Log in as target user
        Auth::login($target);

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return redirect('/')->with('success', "Now impersonating: {$target->email}");
    }

    public function stop(Request $request)
    {
        $originalId = session('impersonate_original_id');

        if (!$originalId) {
            return redirect('/')->withErrors('Not impersonating any user.');
        }

        // Restore original admin session
        $admin = User::findOrFail($originalId);
        Auth::login($admin);

        session()->forget('impersonate_original_id');

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return redirect('/')->with('success', 'Stopped impersonation.');
    }
}
