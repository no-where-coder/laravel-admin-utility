@extends('admin-utility::layout')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">📄 All Tables</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($tables as $table)
            <a href="{{ route('admin.crud.records', $table) }}"
               class="block bg-white border border-gray-200 rounded shadow-sm p-4 hover:border-indigo-500 hover:shadow-md transition">
                <div class="text-sm text-gray-500">Table</div>
                <div class="text-lg font-semibold text-indigo-600">{{ $table }}</div>
            </a>
        @endforeach
    </div>
@endsection
