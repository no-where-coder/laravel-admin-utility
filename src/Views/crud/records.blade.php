@extends('admin-utility::layout')
@section('content')
    <h2>{{ $table }} Records</h2>

    <form method="GET" action="">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <a href="{{ route('admin.crud.create', $table) }}">Create New</a>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                @foreach ($columns as $col)
                    <th>{{ $col }}</th>
                @endforeach
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    @foreach ($columns as $col)
                        <td>{{ $record->$col }}</td>
                    @endforeach
                    <td>
                        <a href="{{ route('admin.crud.edit', [$table, $record->id]) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.crud.destroy', [$table, $record->id]) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        <a href="{{ route('admin.impersonate', $record->id) }}" class="text-blue-600 hover:underline">
                            Impersonate
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $records->withQueryString()->links() }}
@endsection
