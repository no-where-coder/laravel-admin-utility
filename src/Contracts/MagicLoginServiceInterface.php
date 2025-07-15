<?php

namespace Nowhere\AdminUtility\Contracts;

use Illuminate\Http\Request;
use App\Models\User;

interface MagicLoginServiceInterface
{
    public function login(Request $request): User;
}
