<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileUploadController
{
    public function index(Request $request)
    {
        $basePath = public_path('/');
        $currentFolder = $request->get('folder', '');

        $folderPath = $basePath . ($currentFolder ? '/' . $currentFolder : '');

        if (!is_dir($folderPath)) {
            abort(404, 'Folder does not exist');
        }

        $files = File::files($folderPath);
        $folders = File::directories($folderPath);

        return view('admin-utility::upload.index', [
            'files' => $files,
            'folders' => $folders,
            'currentFolder' => $currentFolder,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
            'folder' => 'nullable|string',
        ]);

        $folder = $request->input('folder');
        $destination = public_path($folder ? $folder : '');

        if (!is_dir($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $file->move($destination, $name);

        return redirect()->route('admin.upload.index', ['folder' => $folder])->with('success', 'File uploaded.');
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string',
            'current' => 'nullable|string',
        ]);

        $folderPath = public_path(trim(($request->current ? $request->current . '/' : '') . $request->folder_name, '/'));

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        return redirect()->route('admin.upload.index', ['folder' => $request->current])->with('success', 'Folder created.');
    }

    public function deleteFile(Request $request)
    {
        $path = public_path(trim(($request->folder ? $request->folder . '/' : '') . $request->filename, '/'));

        if (File::exists($path)) {
            File::delete($path);
        }

        return redirect()->route('admin.upload.index', ['folder' => $request->folder])->with('success', 'File deleted.');
    }

    public function deleteFolder(Request $request)
    {
        $folder = trim($request->folder, '/');
        $path = public_path($folder);

        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
        }

        return redirect()->route('admin.upload.index')->with('success', 'Folder deleted.');
    }
}
