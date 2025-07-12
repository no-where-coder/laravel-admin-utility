@extends('admin-utility::layout')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">📝 Create Record in <code>{{ $table }}</code></h2>
    </div>

    <form method="POST" action="{{ route('admin.crud.store', $table) }}" class="space-y-5">
        @csrf

        @foreach ($columns as $column)
            @if (in_array($column, ['id', 'created_at', 'updated_at']))
                @continue
            @endif

            <div>
                <label class="block mb-1 text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $column)) }}</label>
                <input type="text" name="{{ $column }}" value="{{ old($column) }}"
                       class="w-full border rounded px-3 py-2 text-sm">
            </div>
        @endforeach

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ➕ Create
        </button>
    </form>
@endsection
