<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Nowhere\AdminUtility\Contracts\CrudServiceInterface;

class CrudController
{
    public function __construct(
        protected CrudServiceInterface $crud
    ) {}

    public function index()
    {
        $tables = $this->crud->getTables();
        return view('admin-utility::crud.index', compact('tables'));
    }

    public function records(Request $request, string $table)
    {
        if (!$this->crud->tableExists($table)) {
            abort(404);
        }

        $search = $request->query('search');
        $records = $this->crud->getPaginatedRecords($table, $search);
        $columns = $this->crud->getTableColumns($table);

        return view('admin-utility::crud.records', compact('table', 'records', 'columns', 'search'));
    }

    public function create(string $table)
    {
        if (!$this->crud->tableExists($table)) {
            abort(404);
        }

        $columns = $this->crud->getTableColumns($table);
        return view('admin-utility::crud.create', compact('table', 'columns'));
    }

    public function store(Request $request, string $table)
    {
        $data = $request->except('_token');
        $this->crud->store($table, $data);

        return Redirect::route('admin.crud.records', $table)->with('success', 'Record added.');
    }

    public function edit(string $table, int|string $id)
    {
        if (!$this->crud->tableExists($table)) {
            abort(404);
        }

        $columns = $this->crud->getTableColumns($table);
        $record = $this->crud->getRecord($table, $id);

        return view('admin-utility::crud.edit', compact('table', 'columns', 'record'));
    }

    public function update(Request $request, string $table, int|string $id)
    {
        $data = $request->except(['_token', '_method']);
        $this->crud->update($table, $id, $data);

        return Redirect::route('admin.crud.records', $table)->with('success', 'Record updated.');
    }

    public function destroy(string $table, int|string $id)
    {
        $this->crud->destroy($table, $id);

        return Redirect::route('admin.crud.records', $table)->with('success', 'Record deleted.');
    }
}
