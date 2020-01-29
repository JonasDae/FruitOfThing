@extends('layouts.app')

@section('content')
    <div class="container pt-3">
        <div class="row mt-3 font-weight-bold d-none d-md-flex">
            <div class="col-lg-2 col-md-3">Naam</div>
            <div class="col-lg-2 col-md-3">Fruitsoort <a href="{{ route('fruits') }}"><i class="fas fa-external-link-alt"></i></a></div>
            <div class="col-lg-7 col-md-5">Adres</div>
            <div class="col-lg-1 col-md-1 text-right">Postcode</div>
        </div>
        <hr>
        @foreach ($fields as $field)
            <div class="row">
                <div class="col-lg-2 col-md-3 col-12">
                    <span class="font-weight-bolder">{{ $field->name }}</span>
                </div>
                <div class="col-lg-2 col-md-2 col-12">
                    {{ $field->fruit_type->name }}
                </div>
                <div class="col-lg-7 col-md-2 col-10">
                    {{ $field->adres }}
                </div>
                <div class="col-lg-1 col-md-1 col-2 text-right">
                    {{ $field->postcode }}
                </div>
            </div>
            <hr>
        @endforeach
    </div>
@endsection
