<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Nowhere\AdminUtility\Contracts\MaintenanceServiceInterface;

class MaintenanceController
{
    public function __construct(
        protected MaintenanceServiceInterface $maintenanceService
    ) {}

    public function index(): View
    {
        $isDown = $this->maintenanceService->isDown();
        return view('admin-utility::maintenance.index', compact('isDown'));
    }

    public function toggle(): RedirectResponse
    {
        if ($this->maintenanceService->isDown()) {
            $this->maintenanceService->disable();
            $message = 'Maintenance mode disabled.';
        } else {
            $this->maintenanceService->enable();
            $message = 'Maintenance mode enabled.';
        }

        return redirect()->route('admin.maintenance.index')->with('success', $message);
    }
}
