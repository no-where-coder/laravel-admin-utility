@extends('admin-utility::layout')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">✏️ Editing: <code class="text-indigo-600">{{ $filename }}</code></h2>
    </div>

    <form method="POST" action="{{ route('admin.env.update', $filename) }}" class="space-y-4">
        @csrf

        <textarea name="env_content" rows="25"
                  class="w-full text-sm font-mono border border-gray-300 rounded-lg p-4 text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                  spellcheck="false">{{ old('env_content', $content) }}</textarea>

        <button type="submit"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            💾 Save Changes
        </button>
    </form>
@endsection
