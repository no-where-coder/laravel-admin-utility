<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Nowhere\AdminUtility\Contracts\ImpersonationServiceInterface;

class ImpersonationController
{
    public function __construct(
        protected ImpersonationServiceInterface $impersonationService
    ) {}

    public function impersonate(Request $request, int $id)
    {
        try {
            $targetUser = $this->impersonationService->impersonate($request, $id);
        } catch (\Throwable $e) {
            return redirect('/')->withErrors('Impersonation failed: ' . $e->getMessage());
        }

        return redirect('/')->with('success', "Now impersonating: {$targetUser->email}");
    }

    public function stop(Request $request)
    {
        try {
            $admin = $this->impersonationService->stopImpersonation($request);
        } catch (\Throwable $e) {
            return redirect('/')->withErrors('Failed to stop impersonation: ' . $e->getMessage());
        }

        return redirect('/')->with('success', 'Stopped impersonation.');
    }
}
