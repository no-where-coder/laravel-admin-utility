@extends('admin-utility::layout')

@section('content')
    <h2 class="text-xl font-bold mb-4">Editing: <code>{{ $filename }}</code></h2>

    <form method="POST" action="{{ route('admin.env.update', $filename) }}">
        @csrf
        <textarea name="env_content" rows="25" class="w-full border p-3 font-mono text-sm rounded" spellcheck="false">{{ old('env_content', $content) }}</textarea>

        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Save Changes</button>
    </form>
@endsection
