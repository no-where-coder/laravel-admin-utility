@extends('admin-utility::layout')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">✏️ Edit Record #{{ $record->id }} in <code>{{ $table }}</code></h2>
    </div>

    <form method="POST" action="{{ route('admin.crud.update', [$table, $record->id]) }}" class="space-y-5">
        @csrf
        @method('PUT')

        @foreach ($columns as $column)
            @if (in_array($column, ['id', 'created_at', 'updated_at']))
                @continue
            @endif

            <div>
                <label class="block mb-1 text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $column)) }}</label>
                <input type="text" name="{{ $column }}"
                       value="{{ old($column, $record->$column) }}"
                       class="w-full border rounded px-3 py-2 text-sm">
            </div>
        @endforeach

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            ✅ Update
        </button>
    </form>
@endsection
