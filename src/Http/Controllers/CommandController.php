<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CommandController
{
    public function index()
    {
        $predefined = [
            'cache:clear',
            'route:clear',
            'config:clear',
            'view:clear',
            'migrate',
            'route:list',
            'optimize:clear',
            'queue:restart',
            'schedule:run',
        ];

        return view('admin-utility::artisan.index', [
            'commands' => $predefined,
            'output' => null,
            'selected' => null,
        ]);
    }

    public function run(Request $request)
    {
        $request->validate([
            'command' => 'nullable|string',
            'custom_command' => 'nullable|string',
        ]);

        $command = $request->input('command') ?: $request->input('custom_command');

        if (!$command) {
            return redirect()->back()->withErrors(['Please provide a command.']);
        }

        // Blacklist unsafe commands
        $dangerous = [];

        foreach ($dangerous as $black) {
            if (str_contains($command, $black)) {
                return redirect()->back()->withErrors(["Command '{$black}' is not allowed."]);
            }
        }

        // Run command
        try {
            Artisan::call($command);
            $output = Artisan::output();
        } catch (\Exception $e) {
            $output = 'Error: ' . $e->getMessage();
        }

        $predefined = [
            'cache:clear',
            'route:clear',
            'config:clear',
            'view:clear',
            'migrate',
            'route:list',
            'optimize:clear',
            'queue:restart',
            'schedule:run',
        ];

        return view('admin-utility::artisan.index', [
            'commands' => $predefined,
            'output' => $output,
            'selected' => $command,
        ]);
    }
}
