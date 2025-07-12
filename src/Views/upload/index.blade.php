@extends('admin-utility::layout')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">📤 Upload Files</h2>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Upload Form --}}
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.upload.store') }}" class="mb-4 space-y-2">
        @csrf
        <input type="hidden" name="folder" value="{{ $currentFolder }}">

        <input type="file" name="file" class="block w-full border rounded px-3 py-2 text-sm">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Upload</button>
    </form>

    {{-- Folder Creation --}}
    <form method="POST" action="{{ route('admin.upload.create-folder') }}" class="mb-6 flex space-x-2 items-center">
        @csrf
        <input type="hidden" name="current" value="{{ $currentFolder }}">
        <input type="text" name="folder_name" placeholder="New Folder Name" class="border px-3 py-2 rounded text-sm w-1/2">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create Folder</button>
    </form>

    <h3 class="text-sm font-semibold text-gray-700 mb-2">
        📁 Current Folder: <span class="text-gray-900">/public{{ $currentFolder ? '/' . $currentFolder : '' }}</span>
    </h3>

    @if($currentFolder)
        <a href="{{ route('admin.upload.index') }}" class="inline-block mb-4 text-blue-600 hover:underline text-sm">⬅ Back to root</a>
    @endif

    {{-- Folders --}}
    <h4 class="text-base font-medium text-gray-700 mb-2">📁 Folders</h4>
    <ul class="divide-y border rounded bg-white shadow-sm mb-6">
        @forelse($folders as $folder)
            @php
                $folderName = basename($folder);
                $path = trim(($currentFolder ? $currentFolder . '/' : '') . $folderName, '/');
            @endphp
            <li class="flex items-center justify-between px-4 py-2 hover:bg-gray-50 transition">
                <a href="{{ route('admin.upload.index', ['folder' => $path]) }}" class="text-blue-700 font-medium">📁 {{ $folderName }}</a>
                <form method="POST" action="{{ route('admin.upload.delete-folder') }}" onsubmit="return confirm('Delete folder?')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="folder" value="{{ $path }}">
                    <button class="text-red-600 hover:underline text-sm">🗑️</button>
                </form>
            </li>
        @empty
            <li class="px-4 py-2 text-gray-500">No folders found.</li>
        @endforelse
    </ul>

    {{-- Files --}}
    <h4 class="text-base font-medium text-gray-700 mb-2">📄 Files</h4>
    <ul class="divide-y border rounded bg-white shadow-sm">
        @forelse($files as $file)
            <li class="flex items-center justify-between px-4 py-2 hover:bg-gray-50 transition">
                <a href="{{ asset(($currentFolder ? $currentFolder . '/' : '') . $file->getFilename()) }}" target="_blank" class="text-gray-800 hover:text-blue-700">{{ $file->getFilename() }}</a>
                <form method="POST" action="{{ route('admin.upload.delete-file') }}" onsubmit="return confirm('Delete file?')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="folder" value="{{ $currentFolder }}">
                    <input type="hidden" name="filename" value="{{ $file->getFilename() }}">
                    <button class="text-red-600 hover:underline text-sm">🗑️</button>
                </form>
            </li>
        @empty
            <li class="px-4 py-2 text-gray-500">No files found.</li>
        @endforelse
    </ul>
@endsection
