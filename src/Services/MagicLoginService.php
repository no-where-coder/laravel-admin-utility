<?php

namespace Nowhere\AdminUtility\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nowhere\AdminUtility\Contracts\MagicLoginServiceInterface;
use Nowhere\AdminUtility\Services\AdminLogger;

class MagicLoginService implements MagicLoginServiceInterface
{
    protected AdminLogger $logger;

    public function __construct(AdminLogger $logger)
    {
        $this->logger = $logger;
    }

    public function login(Request $request): User
    {
        try {
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
        } catch (\Throwable $e) {
            $this->logger->error("MagicLoginService login error: " . $e->getMessage());
            throw $e;
        }
    }
}
