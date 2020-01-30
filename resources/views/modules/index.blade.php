@extends('layouts.app')

@section('content')
    <div class="container pt-3">
        <div class="row mt-3 mx-0 font-weight-bold d-none d-md-flex">
            <div class="col-lg-2 col-md-2">Naam</div>
            <div class="col-lg-2 col-md-2">Veld <a href="{{ route('fields.index') }}"><i class="fas fa-external-link-alt"></i></a>
            </div>
            <div class="col-lg-2 col-md-2">GSM nummer</div>
            <div class="col-lg-1 col-md-1">Batterij</div>
            <div class="col-lg-2 col-md-2">Uptime</div>
            <div class="col-lg-2 col-md-2">Laatste connectie</div>
            <div class="col-lg-1 col-md-1"></div>
        </div>
        <hr class="mb-0">

        <div id="moduleAccordion">
            @foreach ($modules as $key=>$module)
                <div class="record">
                    {{--Rows--}}
                    <div class="row py-3 mx-0">
                        <div class="col-lg-2 col-md-2 col-12">
                            <span class="font-weight-bolder">{{ $module->name }}</span>
                        </div>
                        <div class="col-lg-2 col-md-2 col-4">
                            <a href="javascript:void" data-toggle="popover" title="{{ $module->field->name }}"
                               data-html="true"
                               data-trigger="hover"
                               data-content="Type: {{ $module->field->fruit_type->name }}<br>
                       Adres: {{ $module->field->adres }}<br>
                       Postcode: {{ $module->field->postcode }}">{{ $module->field->name }}
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-2 col">
                            {{ $module->phone_number }}
                        </div>
                        <div class="col-lg-1 col-md-1 col-4 text-md-left text-right">
                            <span class="d-md-none"><i
                                    class="fas fa-battery-full"></i> </span>{{ $module->battery_level }}%
                        </div>
                        <div class="col-lg-2 col-md-2 col-auto">
                            {{ $module->uptime }}
                        </div>
                        <div class="col-lg-2 col-md-2 col-6">
                            {{ date_create_from_format('Y-m-d H:i:s', $module->last_connection)->format('d/m/Y H:i:s') }}
                        </div>
                        {{--CRUD--}}
                        <div class="col-lg-1 col-md-1 col-6">
                            <a href="javascript:void" data-toggle="collapse" data-target="#collapse{{ $module->id }}"
                               aria-expanded="true" aria-controls="collapse{{ $module->id }}"><i
                                    class="fas fa-edit text-warning mx-1"></i></a>
                            <form id="form{{ $module->id }}" action="{{ route('modules.destroy', $module->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <a href="javascript:void" onclick="document.getElementById('form{{ $module->id }}').submit();""><i class='far fa-trash-alt text-danger mx-1'></i></a>
                            </form>
                        </div>
                    </div>
                    {{--Accordions--}}
                    <div id="collapse{{ $module->id }}" class="collapse" aria-labelledby="headingOne"
                         data-parent="#moduleAccordion">
                        <div class="row mx-0 px-3">
                            <form action="{{ route('modules.update') }}" method="post" class="w-100 mb-3">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $module->id }}">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-12">
                                        <input name="name" type="text" value="{{ old('name') ?? $module->name }}"
                                               class="form-control"
                                               required>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-12">
                                        <select name="field" id="field{{$key}}" class="form-control">
                                            @foreach($fields as $field)
                                                <option value="{{ $field->id }}">{{ $field->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-12">
                                        <input name="phone_number" type="number"
                                               value="{{ old('phone_number') ?? $module->phone_number }}"
                                               class="form-control"
                                               required>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col text-right">
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr class="m-0">
            @endforeach
        </div>
    </div>
@endsection
