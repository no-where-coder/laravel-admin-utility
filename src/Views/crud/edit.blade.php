{{-- File: resources/views/vendor/admin-utility/crud/edit.blade.php --}}
@extends('admin-utility::layout')

@section('content')
    <h2 class="text-xl font-bold mb-4">Edit Record #{{ $record->id }} in <code>{{ $table }}</code></h2>

    <form method="POST" action="{{ route('admin.crud.update', [$table, $record->id]) }}" class="space-y-4">
        @csrf
        @method('PUT')

        @foreach ($columns as $column)
            @if (in_array($column, ['id', 'created_at', 'updated_at']))
                @continue
            @endif

            <div>
                <label class="block font-medium mb-1">{{ ucfirst(str_replace('_', ' ', $column)) }}</label>
                <input type="text" name="{{ $column }}" class="w-full border px-3 py-2 rounded"
                    value="{{ old($column, $record->$column) }}">
            </div>
        @endforeach

        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Update</button>
    </form>
@endsection
