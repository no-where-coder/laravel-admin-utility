@extends('admin-utility::layout')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">⚙️ Artisan Commands</h2>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200 text-sm text-red-800">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.artisan.run') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Select Predefined Command
            </label>
            <select name="command" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">-- Optional --</option>
                @foreach($commands as $cmd)
                    <option value="{{ $cmd }}" {{ $selected == $cmd ? 'selected' : '' }}>{{ $cmd }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Or Enter Custom Command
            </label>
            <input type="text" name="custom_command" value="{{ old('custom_command') }}"
                placeholder="e.g., make:model Post -m"
                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div>
            <button type="submit"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                ▶️ Run Command
            </button>
        </div>
    </form>

    @if($output)
        <div class="mt-10">
            <h3 class="text-lg font-medium text-gray-800 mb-2">🖥 Output</h3>
            <pre class="bg-gray-900 text-green-300 text-sm p-4 rounded-md overflow-x-auto whitespace-pre-wrap">{{ $output }}</pre>
        </div>
    @endif
@endsection
