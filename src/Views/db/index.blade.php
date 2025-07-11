@extends('admin-utility::layout')

@section('content')
    <h2 class="text-xl font-bold mb-4">Database Backup & Restore</h2>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="bg-red-200 text-red-800 p-2 mb-4 rounded">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="mb-8">
        <form method="POST" action="{{ route('admin.db.backup') }}">
            @csrf
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Download Backup (.sql)</button>
        </form>
    </div>

    <div>
        <form method="POST" action="{{ route('admin.db.restore') }}" enctype="multipart/form-data">
            @csrf
            <label class="block mb-2 font-medium">Upload .sql File</label>
            <input type="file" name="sql_file" class="mb-2">
            <button class="bg-green-600 text-white px-4 py-2 rounded">Restore Database</button>
        </form>
    </div>
@endsection
