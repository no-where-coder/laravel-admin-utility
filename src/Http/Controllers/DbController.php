<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Nowhere\AdminUtility\Contracts\DbBackupServiceInterface;

class DbController
{
    public function __construct(
        protected DbBackupServiceInterface $backupService
    ) {}

    public function index()
    {
        return view('admin-utility::db.index');
    }

    public function backup()
    {
        try {
            $path = $this->backupService->generateBackup();
            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            return back()->withErrors(['backup' => 'Backup failed: ' . $e->getMessage()]);
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|mimes:sql,txt|max:51200', // max 50MB
        ]);

        try {
            $this->backupService->restoreBackup($request->file('sql_file'));
        } catch (\Throwable $e) {
            return back()->withErrors(['restore' => $e->getMessage()]);
        }

        return back()->with('success', 'Database restored successfully.');
    }
}
