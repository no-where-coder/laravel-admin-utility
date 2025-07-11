@extends('admin-utility::layout')
@section('content')
    <h1>All Tables</h1>
    <ul>
        @foreach ($tables as $table)
            <li>
                <a href="{{ route('admin.crud.records', $table) }}">{{ $table }}</a>
            </li>
        @endforeach
    </ul>
@endsection
