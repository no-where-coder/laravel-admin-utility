<?php

namespace Nowhere\AdminUtility\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Nowhere\AdminUtility\Contracts\DbBackupServiceInterface;

class DbBackupService implements DbBackupServiceInterface
{
    public function generateBackup(): string
    {
        ini_set('memory_limit', '512M');

        $filename = 'backup_' . now()->format('Ymd_His') . '.sql';
        $path = storage_path("app/{$filename}");

        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $dbKey = 'Tables_in_' . $dbName;

        $sql = "-- Laravel DB Backup\n-- Generated: " . now() . "\n\nSET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $tableObj) {
            $table = $tableObj->$dbKey;
            $create = DB::select("SHOW CREATE TABLE `$table`")[0]->{'Create Table'};

            $sql .= "\n-- Structure for table `$table`\n";
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $sql .= $create . ";\n\n";

            $query = DB::table($table);
            $hasId = Schema::hasColumn($table, 'id');

            if ($hasId) {
                $query->orderBy('id');
            } else {
                $columns = Schema::getColumnListing($table);
                $fallback = $columns[0] ?? null;
                if ($fallback) {
                    $query->orderBy($fallback);
                } else {
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

        return $path;
    }

    public function restoreBackup(UploadedFile $file): void
    {
        $sql = file_get_contents($file->getRealPath());

        DB::beginTransaction();
        try {
            DB::unprepared($sql);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new \RuntimeException("Restore failed: " . $e->getMessage());
        }
    }
}
