<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class DbController
{
    public function index()
    {
        return view('admin-utility::db.index');
    }

    public function backup()
    {
        ini_set('memory_limit', '512M'); // Optional memory increase

        $filename = 'backup_' . now()->format('Ymd_His') . '.sql';
        $path = storage_path("app/{$filename}");
    
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $dbKey = 'Tables_in_' . $dbName;
    
        $sql = "-- Laravel DB Backup\n-- Generated: " . now() . "\n\nSET FOREIGN_KEY_CHECKS=0;\n\n";
    
        foreach ($tables as $tableObj) {
            $table = $tableObj->$dbKey;
    
            // Get CREATE TABLE
            $create = DB::select("SHOW CREATE TABLE `$table`")[0]->{'Create Table'};
            $sql .= "\n-- Structure for table `$table`\n";
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $sql .= $create . ";\n\n";
    
            // Get rows chunked to avoid memory issue
            $hasId = Schema::hasColumn($table, 'id');
    
            $query = DB::table($table);
            if ($hasId) {
                $query->orderBy('id');
            } else {
                // Try fallback to first column
                $columns = Schema::getColumnListing($table);
                $fallbackCol = $columns[0] ?? null;
                if ($fallbackCol) {
                    $query->orderBy($fallbackCol);
                } else {
                    // Skip if no columns to order
                    $sql .= "-- Skipping data export for table `$table` (no orderable column)\n\n";
                    continue;
                }
            }
    
            $query->chunk(500, function ($rows) use (&$sql, $table) {
                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $columns = array_map(fn($col) => "`$col`", array_keys($rowArray));
                    $values = array_map(function ($val) {
                        return is_null($val) ? 'NULL' : "'" . addslashes($val) . "'";
                    }, array_values($rowArray));
    
                    $sql .= "INSERT INTO `$table` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                }
            });
    
            $sql .= "\n";
        }
    
        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
    
        file_put_contents($path, $sql);
    
        return response()->download($path)->deleteFileAfterSend(true);
    }
    
    public function restore(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|mimes:sql,txt|max:51200', // max 50MB
        ]);

        $file = $request->file('sql_file');
        $sql = file_get_contents($file->getRealPath());

        try {
            DB::beginTransaction();
            DB::unprepared($sql);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['restore' => 'Restore failed: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Database restored successfully.');
    }
}
