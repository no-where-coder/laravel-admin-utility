<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Nowhere\AdminUtility\Contracts\FileManagerInterface;

class FileUploadController
{
    public function __construct(
        protected FileManagerInterface $fileManager
    ) {}

    public function index(Request $request)
    {
        $currentFolder = $request->get('folder', '');

        try {
            $files = $this->fileManager->listFiles($currentFolder);
            $folders = $this->fileManager->listFolders($currentFolder);
        } catch (\Throwable $e) {
            abort(404, 'Folder does not exist');
        }

        return view('admin-utility::upload.index', compact('files', 'folders', 'currentFolder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
            'folder' => 'nullable|string',
        ]);

        try {
            $this->fileManager->uploadFile($request->file('file'), $request->input('folder', ''));
        } catch (\Throwable $e) {
            return back()->withErrors(['upload' => 'Upload failed: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.upload.index', ['folder' => $request->input('folder')])
            ->with('success', 'File uploaded.');
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string',
            'current' => 'nullable|string',
        ]);

        $folder = trim(($request->current ? $request->current . '/' : '') . $request->folder_name, '/');

        try {
            $this->fileManager->createFolder($folder);
        } catch (\Throwable $e) {
            return back()->withErrors(['create' => 'Folder creation failed: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.upload.index', ['folder' => $request->current])
            ->with('success', 'Folder created.');
    }

    public function deleteFile(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'folder' => 'nullable|string',
        ]);

        $path = trim(($request->folder ? $request->folder . '/' : '') . $request->filename, '/');

        try {
            $this->fileManager->deleteFile($path);
        } catch (\Throwable $e) {
            return back()->withErrors(['delete' => 'File deletion failed: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.upload.index', ['folder' => $request->folder])
            ->with('success', 'File deleted.');
    }

    public function deleteFolder(Request $request)
    {
        $request->validate([
            'folder' => 'required|string',
        ]);

        try {
            $this->fileManager->deleteFolder($request->folder);
        } catch (\Throwable $e) {
            return back()->withErrors(['delete' => 'Folder deletion failed: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.upload.index')
            ->with('success', 'Folder deleted.');
    }
}
