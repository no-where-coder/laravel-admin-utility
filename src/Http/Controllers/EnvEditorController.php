<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class EnvEditorController
{
    protected $envDir;

    public function __construct()
    {
        $this->envDir = base_path(); // usually contains .env files
    }

    public function index()
    {
        $files = collect(glob(base_path('.env*')))
        ->map(fn($path) => basename($path))
        ->values();

        return view('admin-utility::env.index', compact('files'));
    }

    public function edit($filename)
    {
        $path = $this->resolveEnvPath($filename);

        if (!File::exists($path)) {
            abort(404, "$filename not found.");
        }

        $content = File::get($path);
        return view('admin-utility::env.edit', compact('filename', 'content'));
    }

    public function update(Request $request, $filename)
    {
        $request->validate([
            'env_content' => 'required|string',
        ]);

        $path = $this->resolveEnvPath($filename);

        if (!File::exists($path)) {
            abort(404, "$filename not found.");
        }

        File::put($path, $request->input('env_content'));

        // Optional: reload config for base `.env` only
        if ($filename === '.env') {
            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }

        return back()->with('success', "$filename updated successfully.");
    }

    protected function resolveEnvPath($filename)
    {
        $safe = basename($filename); // prevent directory traversal
        return base_path($safe);
    }
}
