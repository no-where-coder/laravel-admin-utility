@extends('admin-utility::layout')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">📑 {{ ucfirst($table) }} Records</h2>
    </div>

    <form method="GET" action="" class="mb-4 flex flex-wrap gap-2 items-center">
        <input type="text" name="search" value="{{ request('search') }}"
               class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/3 text-sm" placeholder="Search...">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
            🔍 Search
        </button>
    </form>

    <div class="mb-4">
        <a href="{{ route('admin.crud.create', $table) }}"
           class="inline-flex items-center text-sm font-medium bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
            ➕ Create New
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border border-gray-300 text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    @foreach ($columns as $col)
                        <th class="p-2 border text-left font-semibold">{{ $col }}</th>
                    @endforeach
                    <th class="p-2 border text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    <tr class="hover:bg-gray-50">
                        @foreach ($columns as $col)
                            <td class="p-2 border text-xs text-gray-800">{{ $record->$col }}</td>
                        @endforeach
                        <td class="p-2 border text-xs flex gap-3 items-center">
                            <a href="{{ route('admin.crud.edit', [$table, $record->id]) }}"
                               class="text-blue-600 hover:underline">✏️ Edit</a>
                            <form method="POST" action="{{ route('admin.crud.destroy', [$table, $record->id]) }}"
                                  onsubmit="return confirm('Are you sure?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">🗑️ Delete</button>
                            </form>
                            <a href="{{ route('admin.impersonate', $record->id) }}"
                               class="text-indigo-600 hover:underline">👤 Impersonate</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Custom Tailwind Pagination --}}
    @if ($records->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span class="font-medium">{{ $records->firstItem() }}</span> to
                <span class="font-medium">{{ $records->lastItem() }}</span> of
                <span class="font-medium">{{ $records->total() }}</span> results
            </div>

            <div class="mb-16">
                <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
                    {{-- Previous Page Link --}}
                    @if ($records->onFirstPage())
                        <span class="relative inline-flex items-center px-3 py-1 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-l-md">← Prev</span>
                    @else
                        <a href="{{ $records->previousPageUrl() }}"
                           class="relative inline-flex items-center px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-l-md">
                            ← Prev
                        </a>
                    @endif

                    {{-- Page Number Links --}}
                    @foreach ($records->getUrlRange(1, $records->lastPage()) as $page => $url)
                        @if ($page == $records->currentPage())
                            <span class="z-10 inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-indigo-600 border border-indigo-600">{{ $page }}</span>
                        @elseif ($page <= 2 || $page > $records->lastPage() - 2 || ($page >= $records->currentPage() - 1 && $page <= $records->currentPage() + 1))
                            <a href="{{ $url }}"
                               class="inline-flex items-center px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-100">
                                {{ $page }}
                            </a>
                        @elseif ($page == 3 || $page == $records->lastPage() - 2)
                            <span class="px-2">...</span>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($records->hasMorePages())
                        <a href="{{ $records->nextPageUrl() }}"
                           class="relative inline-flex items-center px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-r-md">
                            Next →
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-3 py-1 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-r-md">Next →</span>
                    @endif
                </nav>
            </div>
        </div>
    @endif
@endsection
