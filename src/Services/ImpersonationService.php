<?php

namespace Nowhere\AdminUtility\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nowhere\AdminUtility\Contracts\ImpersonationServiceInterface;
use Nowhere\AdminUtility\Services\AdminLogger;

class ImpersonationService implements ImpersonationServiceInterface
{
    protected const SESSION_KEY = 'impersonate_original_id';

    protected AdminLogger $logger;

    public function __construct(AdminLogger $logger)
    {
        $this->logger = $logger;
    }

    public function impersonate(Request $request, int $targetUserId): User
    {
        try {
            $target = User::findOrFail($targetUserId);

            // Save original admin ID
            Session::put(self::SESSION_KEY, Auth::id());

            Auth::login($target);

            if ($request->hasSession()) {
                $request->session()->regenerate();
            }

            return $target;
        } catch (\Throwable $e) {
            $this->logger->error("ImpersonationService impersonate error: " . $e->getMessage());
            throw $e;
        }
    }

    public function stopImpersonation(Request $request): User
    {
        try {
            $originalId = Session::get(self::SESSION_KEY);

            if (!$originalId) {
                throw new \RuntimeException('No impersonation session found.');
            }

            $admin = User::findOrFail($originalId);

            Auth::login($admin);
            Session::forget(self::SESSION_KEY);

            if ($request->hasSession()) {
                $request->session()->regenerate();
            }

            return $admin;
        } catch (\Throwable $e) {
            $this->logger->error("ImpersonationService stopImpersonation error: " . $e->getMessage());
            throw $e;
        }
    }
}
