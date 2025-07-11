<?php

namespace Nowhere\AdminUtility\Contracts;

use Illuminate\Http\Request;
use App\Models\User;

interface ImpersonationServiceInterface
{
    public function impersonate(Request $request, int $targetUserId): User;

    public function stopImpersonation(Request $request): User;
}
