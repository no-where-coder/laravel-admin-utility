@extends('admin-utility::layout')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">💾 Database Backup & Restore</h2>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200 text-sm text-red-800 space-y-1">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- Backup Section -->
    <div class="mb-8">
        <form method="POST" action="{{ route('admin.db.backup') }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                ⬇️ Download Backup (.sql)
            </button>
        </form>
    </div>

    <!-- Restore Section -->
    <div>
        <form method="POST" action="{{ route('admin.db.restore') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload .sql File</label>
                <input type="file" name="sql_file"
                    class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
            </div>

            <button type="submit"
                class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                🔁 Restore Database
            </button>
        </form>
    </div>
@endsection
