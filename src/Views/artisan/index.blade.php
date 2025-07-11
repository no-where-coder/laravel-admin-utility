@extends('admin-utility::layout')

@section('content')
    <h2 class="text-xl font-bold mb-4">⚙️ Run Artisan Commands</h2>

    @if($errors->any())
        <div class="bg-red-100 p-2 mb-4 text-red-600 border border-red-400">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.artisan.run') }}" class="mb-6">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Select Predefined Command:</label>
            <select name="command" class="w-full border rounded px-3 py-2">
                <option value="">-- Optional --</option>
                @foreach($commands as $cmd)
                    <option value="{{ $cmd }}" {{ $selected == $cmd ? 'selected' : '' }}>{{ $cmd }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Or Enter Custom Command:</label>
            <input type="text" name="custom_command" class="w-full border rounded px-3 py-2"
                   placeholder="e.g., make:model Post -m" value="{{ old('custom_command') }}">
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Run</button>
    </form>

    @if($output)
        <h3 class="text-lg font-semibold mb-2">🖥 Output:</h3>
        <pre class="bg-gray-900 text-green-300 p-4 rounded overflow-x-auto text-sm">{{ $output }}</pre>
    @endif
@endsection
