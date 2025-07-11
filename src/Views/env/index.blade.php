@extends('admin-utility::layout')

@section('content')
    <h2 class="text-xl font-bold mb-4">Available .env Files</h2>

    @if(session('success'))
        <div class="p-2 mb-4 bg-green-200 border border-green-400 rounded">{{ session('success') }}</div>
    @endif

    <ul class="list-disc ml-6">
        @forelse ($files as $file)
            <li>
                <a href="{{ route('admin.env.edit', $file) }}" class="text-blue-600 underline">{{ $file }}</a>
            </li>
        @empty
            <li>No .env files found.</li>
        @endforelse
    </ul>
@endsection
