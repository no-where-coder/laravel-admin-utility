@extends('admin-utility::layout')

@section('content')
    <h2 class="text-xl font-bold mb-4">Maintenance Mode</h2>

    @if(session('success'))
        <div class="p-2 mb-4 bg-green-200 border border-green-400">{{ session('success') }}</div>
    @endif

    <p class="mb-4">
        Current Status:
        <span class="font-bold {{ $isDown ? 'text-red-600' : 'text-green-600' }}">
            {{ $isDown ? 'In Maintenance' : 'Live' }}
        </span>
    </p>

    <form method="POST" action="{{ route('admin.maintenance.toggle') }}">
        @csrf
        <button type="submit"
            class="px-4 py-2 rounded text-white {{ $isDown ? 'bg-green-600' : 'bg-red-600' }}">
            {{ $isDown ? 'Disable Maintenance Mode' : 'Enable Maintenance Mode' }}
        </button>
    </form>
@endsection
