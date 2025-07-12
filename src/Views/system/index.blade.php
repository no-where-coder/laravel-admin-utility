@extends('admin-utility::layout')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">🖥 System Information</h2>
    </div>

    <div class="overflow-x-auto rounded shadow">
        <table class="min-w-full bg-white border border-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-2 font-medium text-gray-600 border-r">Key</th>
                    <th class="text-left px-4 py-2 font-medium text-gray-600">Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($info as $key => $value)
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-2 border-r">{{ $key }}</td>
                        <td class="px-4 py-2">{{ $value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
