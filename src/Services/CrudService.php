<?php

namespace Nowhere\AdminUtility\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Nowhere\AdminUtility\Contracts\CrudServiceInterface;
use Nowhere\AdminUtility\Services\AdminLogger;

class CrudService implements CrudServiceInterface
{
    protected AdminLogger $logger;

    public function __construct(AdminLogger $logger)
    {
        $this->logger = $logger;
    }

    public function getTables(): array
    {
        try {
            $tables = DB::select('SHOW TABLES');
            return array_map('current', $tables);
        } catch (\Throwable $e) {
            $this->logger->error("CrudService getTables error: " . $e->getMessage());
            return [];
        }
    }

    public function tableExists(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (\Throwable $e) {
            $this->logger->error("CrudService tableExists error: " . $e->getMessage());
            return false;
        }
    }

    public function getTableColumns(string $table): array
    {
        try {
            return Schema::getColumnListing($table);
        } catch (\Throwable $e) {
            $this->logger->error("CrudService getTableColumns error: " . $e->getMessage());
            return [];
        }
    }

    public function getPaginatedRecords(string $table, ?string $search = null, int $perPage = 15)
    {
        try {
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
        } catch (\Throwable $e) {
            $this->logger->error("CrudService getPaginatedRecords error: " . $e->getMessage());
            return null;
        }
    }

    public function getRecord(string $table, int|string $id): ?object
    {
        try {
            return DB::table($table)->where('id', $id)->first();
        } catch (\Throwable $e) {
            $this->logger->error("CrudService getRecord error: " . $e->getMessage());
            return null;
        }
    }

    public function store(string $table, array $data): void
    {
        try {
            DB::table($table)->insert($data);
        } catch (\Throwable $e) {
            $this->logger->error("CrudService store error: " . $e->getMessage());
            throw $e;
        }
    }

    public function update(string $table, int|string $id, array $data): void
    {
        try {
            DB::table($table)->where('id', $id)->update($data);
        } catch (\Throwable $e) {
            $this->logger->error("CrudService update error: " . $e->getMessage());
            throw $e;
        }
    }

    public function destroy(string $table, int|string $id): void
    {
        try {
            DB::table($table)->where('id', $id)->delete();
        } catch (\Throwable $e) {
            $this->logger->error("CrudService destroy error: " . $e->getMessage());
            throw $e;
        }
    }
}
