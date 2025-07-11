@extends('admin-utility::layout')

@section('content')
    <h2 class="text-xl font-bold mb-4">Upload Files</h2>

    @if(session('success'))
        <div class="p-2 mb-4 bg-green-200 border border-green-400">{{ session('success') }}</div>
    @endif

    {{-- Upload Form --}}
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.upload.store') }}" class="mb-4">
        @csrf
        <input type="hidden" name="folder" value="{{ $currentFolder }}">
        <input type="file" name="file" class="mb-2">
        <button type="submit" class="bg-blue-500 px-4 py-2 text-white rounded">Upload</button>
    </form>

    {{-- Folder Creation --}}
    <form method="POST" action="{{ route('admin.upload.create-folder') }}" class="mb-6">
        @csrf
        <input type="hidden" name="current" value="{{ $currentFolder }}">
        <input type="text" name="folder_name" placeholder="New Folder Name" class="border px-2 py-1">
        <button type="submit" class="bg-green-500 px-3 py-1 text-white rounded">Create Folder</button>
    </form>

    <h3 class="text-lg font-semibold mb-2">📁 Current Folder: /public{{ $currentFolder ? '/' . $currentFolder : '' }}</h3>

    @if($currentFolder)
        <a href="{{ route('admin.upload.index') }}" class="text-blue-500 underline mb-4 inline-block">⬅ Back to root</a>
    @endif

    {{-- Folders --}}
    <h4 class="font-semibold mt-4">Folders:</h4>
    <ul class="list-disc ml-6 mb-4">
        @forelse($folders as $folder)
            @php
                $folderName = basename($folder);
                $path = trim(($currentFolder ? $currentFolder . '/' : '') . $folderName, '/');
            @endphp
            <li class="flex items-center justify-between">
                <a href="{{ route('admin.upload.index', ['folder' => $path]) }}" class="text-blue-600 underline">
                    📁 {{ $folderName }}
                </a>
                <form method="POST" action="{{ route('admin.upload.delete-folder') }}" onsubmit="return confirm('Delete folder?')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="folder" value="{{ $path }}">
                    <button class="text-red-600 text-sm">🗑️</button>
                </form>
            </li>
        @empty
            <li>No folders found.</li>
        @endforelse
    </ul>

    {{-- Files --}}
    <h4 class="font-semibold mt-4">Files:</h4>
    <ul class="list-disc ml-6">
        @forelse($files as $file)
            <li class="flex items-center justify-between">
                <a href="{{ asset(($currentFolder ? $currentFolder . '/' : '') . $file->getFilename()) }}" target="_blank">
                    📄 {{ $file->getFilename() }}
                </a>
                <form method="POST" action="{{ route('admin.upload.delete-file') }}" onsubmit="return confirm('Delete file?')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="folder" value="{{ $currentFolder }}">
                    <input type="hidden" name="filename" value="{{ $file->getFilename() }}">
                    <button class="text-red-600 text-sm">🗑️</button>
                </form>
            </li>
        @empty
            <li>No files found.</li>
        @endforelse
    </ul>
@endsection
