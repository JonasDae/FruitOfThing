@extends('layouts.app')

@section('content')
    <div class="container pt-3">
        <div class="row mt-3 font-weight-bold d-none d-md-flex">
            <div class="col-lg-12 col-md-12">Naam <a href="{{ route('fruits.create') }}"><i class="fas fa-plus-circle text-success"></i></a></div>
        </div>
        <hr>
        @foreach ($fruit_types as $fruit_type)
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    {{ $fruit_type->name }}
                </div>
            </div>
            <hr>
        @endforeach
    </div>
@endsection
