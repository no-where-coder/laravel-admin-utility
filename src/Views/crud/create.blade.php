{{-- File: resources/views/vendor/admin-utility/crud/create.blade.php --}}
@extends('admin-utility::layout')

@section('content')
    <h2 class="text-xl font-bold mb-4">Create Record in <code>{{ $table }}</code></h2>

    <form method="POST" action="{{ route('admin.crud.store', $table) }}" class="space-y-4">
        @csrf

        @foreach ($columns as $column)
            @if (in_array($column, ['id', 'created_at', 'updated_at']))
                @continue
            @endif

            <div>
                <label class="block font-medium mb-1">{{ ucfirst(str_replace('_', ' ', $column)) }}</label>
                <input type="text" name="{{ $column }}" class="w-full border px-3 py-2 rounded" value="{{ old($column) }}">
            </div>
        @endforeach

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
    </form>
@endsection
