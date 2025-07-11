<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class MaintenanceController
{
    public function index()
    {
        $isDown = app()->isDownForMaintenance();
        return view('admin-utility::maintenance.index', compact('isDown'));
    }

    public function toggle()
    {
        if (app()->isDownForMaintenance()) {
            \Artisan::call('up');
            return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance mode disabled.');
        } else {
            // Secret path to allow admin access
            \Artisan::call('down', [
                '--secret' => 'admin-utility-secret'
            ]);
            return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance mode enabled.');
        }
    }
}
