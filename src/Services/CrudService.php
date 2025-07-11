<?php

namespace Nowhere\AdminUtility\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Nowhere\AdminUtility\Contracts\CrudServiceInterface;

class CrudService implements CrudServiceInterface
{
    public function getTables(): array
    {
        $tables = DB::select('SHOW TABLES');
        return array_map('current', $tables);
    }

    public function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }

    public function getTableColumns(string $table): array
    {
        return Schema::getColumnListing($table);
    }

    public function getPaginatedRecords(string $table, ?string $search = null, int $perPage = 15)
    {
        $query = DB::table($table);

        if ($search) {
            $columns = $this->getTableColumns($table);
            $query->where(function ($q) use ($columns, $search) {
                foreach ($columns as $col) {
                    $q->orWhere($col, 'LIKE', "%{$search}%");
                }
            });
        }

        return $query->paginate($perPage);
    }

    public function getRecord(string $table, int|string $id): object|null
    {
        return DB::table($table)->where('id', $id)->first();
    }

    public function store(string $table, array $data): void
    {
        DB::table($table)->insert($data);
    }

    public function update(string $table, int|string $id, array $data): void
    {
        DB::table($table)->where('id', $id)->update($data);
    }

    public function destroy(string $table, int|string $id): void
    {
        DB::table($table)->where('id', $id)->delete();
    }
}
