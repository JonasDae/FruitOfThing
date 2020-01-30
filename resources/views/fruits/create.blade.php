@extends('layouts.app')

@section('content')
    <div class="container pt-3">
        <ul class="breadcrumb">
            <li><a href="{{ route('fruits.index') }}">Fruitsoorten</a></li>
            <li class="active">/ Toevoegen</li>
        </ul>
        <h1>Fruitsoort toevoegen</h1>
        <form action="{{ route('fruits.store') }}" method="post">
            @csrf
            <div class="form-group row">
                <div class="col">
                    <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success">Opslaan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
