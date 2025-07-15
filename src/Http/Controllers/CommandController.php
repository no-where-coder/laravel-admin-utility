<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Nowhere\AdminUtility\Contracts\ConsoleCommandServiceInterface;

class CommandController
{
    public function __construct(
        protected ConsoleCommandServiceInterface $console
    ) {}

    public function index()
    {
        return view('admin-utility::artisan.index', [
            'commands' => $this->console->predefinedCommands(),
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

        if (!$this->console->isSafeCommand($command)) {
            return redirect()->back()->withErrors(["Command contains unsafe keywords and was blocked."]);
        }

        try {
            $output = $this->console->execute($command);
        } catch (\Throwable $e) {
            $output = 'Error: ' . $e->getMessage();
        }

        return view('admin-utility::artisan.index', [
            'commands' => $this->console->predefinedCommands(),
            'output' => $output,
            'selected' => $command,
        ]);
    }
}
