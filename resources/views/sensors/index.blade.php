@extends('layouts.app')

@section('content')
    <div class="container-lg pt-3">
        <div class="row mt-3 mx-0 font-weight-bold">
            <div class="col-md col">Module <a href="{{ route('modules.index') }}"><i class="fas fa-external-link-alt"></i></a></div>
            <div class="col-md col text-center">Type <a href="{{ route('sensor_types.index') }}"><i class="fas fa-external-link-alt"></i></a></div>
            <div class="col-md col-auto text-right">Laatste connectie</div>
        </div>
        <hr class="mb-0">

        @foreach ($sensors as $sensor)
            <div class="record">
                {{--Rows--}}
                <div class="row py-3 mx-0">
                    <div class="col-md col">
                        <a href="javascript:void" data-toggle="popover" title="{{ $sensor->module->name ?? '' }}" data-html="true" data-trigger="hover" data-content="Veld: {{ $sensor->module->field->name ?? '' }}<br>Batterij: {{ $sensor->module->battery_level ?? '' }}%<br>GSM: {{ $sensor->module->phone_number ?? '' }}">{{ $sensor->module->name ?? '' }}</a>
                    </div>
                    <div class="col-md col text-center">
                        <a href="javascript:void" data-toggle="popover" title="{{ $sensor->sensor->name_alias ?? '' }}" data-html="true" data-trigger="hover" data-content="Type: {{ $sensor->sensor->name ?? '' }}<br>Meeteenheid: {{ $sensor->sensor->measuring_unit ?? '' }}<br>Kleur: {{ $sensor->sensor->color ?? '' }}">{{ $sensor->sensor->name_alias ?? '' }}</a>
                    </div>
                    <div class="col-md col-auto text-right">
                        {{ $sensor->last_connection }}
                    </div>
                </div>
            </div>
            <hr class="m-0">
        @endforeach
    </div>
@endsection
