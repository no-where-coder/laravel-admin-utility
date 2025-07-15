@extends('admin-utility::layout')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">🛠 Maintenance Mode</h2>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 text-sm text-gray-700">
        Current Status:
        <span class="inline-block font-semibold ml-2 {{ $isDown ? 'text-red-600' : 'text-green-600' }}">
            {{ $isDown ? 'In Maintenance' : 'Live' }}
        </span>
    </div>

    <form method="POST" action="{{ route('admin.maintenance.toggle') }}">
        @csrf
        <button type="submit"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500
            {{ $isDown ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
            {{ $isDown ? '✅ Disable Maintenance Mode' : '🚧 Enable Maintenance Mode' }}
        </button>
    </form>
@endsection
