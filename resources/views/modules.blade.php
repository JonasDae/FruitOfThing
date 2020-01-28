@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-3 font-weight-bold d-none d-md-flex">
            <div class="col-lg-2 col-md-3">Naam</div>
            <div class="col-lg-2 col-md-2">Veld</div>
            <div class="col-lg-1 col-md-1">Batterij</div>
            <div class="col-lg-2 col-md-2">GSM nummer</div>
            <div class="col-lg-2 col-md-2">Uptime</div>
            <div class="col-lg-2 col-md-2">Laatste connectie</div>
        </div>
        <hr>
        @foreach ($modules as $module)
            <div class="row">
                <div class="col-lg-2 col-md-3 col">
                    {{ $module->name }}
                </div>
                <div class="col-lg-2 col-md-2 col">
                    {{ $module->field->name }}
                </div>
                <div class="col-lg-1 col-md-1 col-auto">
                    {{ $module->battery_level }}
                </div>
                <div class="col-lg-2 col-md-2 col-auto">
                    {{ $module->phone_number }}
                </div>
                <div class="col-lg-2 col-md-2 col-auto">
                    {{ $module->uptime }}
                </div>
                <div class="col-lg-2 col-md-2 col-auto">
                    {{ date_create_from_format('Y-m-d H:i:s', $module->last_connection)->format('d/m/Y H:i:s') }}
                </div>
            </div>
            <hr>
        @endforeach
    </div>
@endsection
