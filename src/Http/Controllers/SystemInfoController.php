<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\View\View;
use Nowhere\AdminUtility\Contracts\SystemInfoServiceInterface;

class SystemInfoController
{
    public function __construct(
        protected SystemInfoServiceInterface $systemInfoService
    ) {}

    public function index(): View
    {
        $info = $this->systemInfoService->getSystemInfo();
        return view('admin-utility::system.index', compact('info'));
    }
}
