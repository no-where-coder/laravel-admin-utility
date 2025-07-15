<?php

namespace Nowhere\AdminUtility\Contracts;

use Illuminate\Http\Request;

interface CrudServiceInterface
{
    public function getTables(): array;

    public function getTableColumns(string $table): array;

    public function getPaginatedRecords(string $table, ?string $search = null, int $perPage = 15);

    public function getRecord(string $table, int|string $id): object|null;

    public function store(string $table, array $data): void;

    public function update(string $table, int|string $id, array $data): void;

    public function destroy(string $table, int|string $id): void;

    public function tableExists(string $table): bool;
}
