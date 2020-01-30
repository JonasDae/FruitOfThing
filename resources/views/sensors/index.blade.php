@extends('layouts.app')

@section('content')
<div class="container pt-3">
<div id="sensorNewAccordion">
<div class="row mt-3 mx-0 font-weight-bold">
        <div class="col">Naam</div>
        <div class="col">Alias</div>
        <div class="col">Eenheid</div>
        <div class="col">Kleur</div>
        <div class="col">Grafiek type</div>
        <div class="col-1 text-right">
            <a href="javascript:void" data-toggle="collapse" data-target="#collapseNew" aria-expanded="true" aria-controls="collapseNew">
                <i class="fas fa-plus-circle text-success mx-1"></i>
            </a>
        </div>
    </div>
    <div id="collapseNew" class="collapse" aria-labelledby="headingOne" data-parent="#sensorNewAccordion">
            <div class="row mx-0 px-3">
                <form action="{{ route('sensors.store') }}" method="post" class="w-100 mb-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" value="">
                    <div class="row">
                        <div class="col-lg col-md col-12">
                            <input name="name" type="text" value=""
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <input name="name_alias" type="text" value=""
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <input name="measuring_unit" type="text" value=""
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <input name="color" type="text" value=""
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <input name="graph_type" type="text" value=""
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg-1 col-md-1 col text-right">
                            <button type="submit" class="btn btn-success">Opslaan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>

    <hr class="mb-0"/>
    <div id="sensorAccordion">
        @foreach ($sensors as $sensor)
        <div class="sensor">
            <div class="row py-3 mx-0">
                <div class="col-12 col-sm">
                    <span class="font-weight-bolder">{{ $sensor->name }}</span>
                </div>
                <div class="col">
                    {{ $sensor->name_alias }}
                </div>
                <div class="col">
                    {{ $sensor->measuring_unit }}
                </div>
                <div class="col">
                    {{ $sensor->color }}
                </div>
                <div class="col">
                    {{ $sensor->graph_type }}
                </div>
                <div class="col-1 text-right">
                    <a href="javascript:void" data-toggle="collapse" data-target="#collapse{{ $sensor->id }}"
                               aria-expanded="true" aria-controls="collapse{{ $sensor->id }}"><i
                                    class="fas fa-edit text-warning mx-1"></i></a>
                    <form id="form{{ $sensor->id }}" action="{{ route('sensors.destroy', $sensor->id) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <a href="javascript:void" onclick="document.getElementById('form{{ $sensor->id }}').submit();"><i class='far fa-trash-alt text-danger mx-1'></i></a>
                    </form>
                </div>
            </div>
        </div>
        <div id="collapse{{ $sensor->id }}" class="collapse" aria-labelledby="headingOne" data-parent="#sensorAccordion">
            <div class="row mx-0 px-3">
                <form action="{{ route('sensors.update') }}" method="post" class="w-100 mb-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ $sensor->id }}">
                    <div class="row">
                        <div class="col-lg col-md col-12">
                            <input name="name" type="text" value="{{ old('name') ?? $sensor->name }}"
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <input name="name_alias" type="text" value="{{ old('name_alias') ?? $sensor->name_alias }}"
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <input name="measuring_unit" type="text" value="{{ old('measuring_unit') ?? $sensor->measuring_unit }}"
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <input name="color" type="text" value="{{ old('color') ?? $sensor->color }}"
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <input name="graph_type" type="text" value="{{ old('graph_type') ?? $sensor->graph_type }}"
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg-1 col-md-1 col text-right">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <hr class="m-0">
        @endforeach
    </div>
</div>
@endsection
