@extends('admin-utility::layout')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">📝 Available .env Files</h2>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <ul class="space-y-2 text-sm text-gray-800">
        @forelse ($files as $file)
            <li>
                <a href="{{ route('admin.env.edit', $file) }}"
                   class="text-indigo-600 hover:underline hover:text-indigo-800 font-medium">
                    {{ $file }}
                </a>
            </li>
        @empty
            <li class="text-gray-500 italic">No .env files found.</li>
        @endforelse
    </ul>
@endsection
