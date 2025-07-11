<?php

namespace Nowhere\AdminUtility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;


class CrudController
{
    public function index()
    {
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);
        return view('admin-utility::crud.index', compact('tables'));
    }

    public function records(Request $request, $table)
    {
        if (!Schema::hasTable($table)) {
            abort(404);
        }

        $query = DB::table($table);
        
        // Optional search support
        $search = $request->query('search');
        if ($search) {
            $columns = Schema::getColumnListing($table);
            $query->where(function ($q) use ($columns, $search) {
                foreach ($columns as $col) {
                    $q->orWhere($col, 'LIKE', "%{$search}%");
                }
            });
        }

        $records = $query->paginate(15);
        $columns = Schema::getColumnListing($table);

        return view('admin-utility::crud.records', compact('table', 'records', 'columns', 'search'));
    }


    public function create($table)
    {
        if (!Schema::hasTable($table)) {
            abort(404);
        }

        $columns = Schema::getColumnListing($table);
        return view('admin-utility::crud.create', compact('table', 'columns'));
    }

    public function store(Request $request, $table)
    {
        $data = $request->except('_token');
        DB::table($table)->insert($data);

        return Redirect::route('admin.crud.records', $table)->with('success', 'Record added.');
    }

    public function edit($table, $id)
    {
        if (!Schema::hasTable($table)) {
            abort(404);
        }

        $columns = Schema::getColumnListing($table);
        $record = DB::table($table)->where('id', $id)->first();

        return view('admin-utility::crud.edit', compact('table', 'columns', 'record'));
    }


    public function update(Request $request, $table, $id)
    {
        $data = $request->except(['_token', '_method']);
        DB::table($table)->where('id', $id)->update($data);

        return Redirect::route('admin.crud.records', $table)->with('success', 'Record updated.');
    }

    public function destroy($table, $id)
    {
        DB::table($table)->where('id', $id)->delete();

        return Redirect::route('admin.crud.records', $table)->with('success', 'Record deleted.');
    }
}