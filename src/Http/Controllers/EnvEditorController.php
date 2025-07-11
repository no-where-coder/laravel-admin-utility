<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Nowhere\AdminUtility\Contracts\EnvEditorServiceInterface;

class EnvEditorController
{
    public function __construct(
        protected EnvEditorServiceInterface $envEditor
    ) {}

    public function index()
    {
        $files = $this->envEditor->listEnvFiles();
        return view('admin-utility::env.index', compact('files'));
    }

    public function edit(string $filename)
    {
        try {
            $content = $this->envEditor->readEnvFile($filename);
        } catch (\Throwable $e) {
            abort(404, $e->getMessage());
        }

        return view('admin-utility::env.edit', compact('filename', 'content'));
    }

    public function update(Request $request, string $filename)
    {
        $request->validate([
            'env_content' => 'required|string',
        ]);

        try {
            $this->envEditor->updateEnvFile($filename, $request->input('env_content'));
        } catch (\Throwable $e) {
            return back()->withErrors(['env' => $e->getMessage()]);
        }

        return back()->with('success', "$filename updated successfully.");
    }
}
