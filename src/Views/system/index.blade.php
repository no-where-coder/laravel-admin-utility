@extends('admin-utility::layout')

@section('content')
    <h2 class="text-xl font-bold mb-4">System Info</h2>

    <table class="w-full table-auto border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="text-left p-2 border">Key</th>
                <th class="text-left p-2 border">Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($info as $key => $value)
                <tr>
                    <td class="p-2 border">{{ $key }}</td>
                    <td class="p-2 border">{{ $value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
