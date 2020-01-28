@extends('layouts.app')

@section('content')
    @foreach ($modules as $module)
        <p>{{ $module->name }} {{ $module->field->name }}</p>
    @endforeach
@endsection
