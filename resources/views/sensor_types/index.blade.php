@extends('layouts.app')

@section('content')
<div class="container pt-3">
    <div id="sensorNewAccordion">
        <div class="row mt-3 mx-0 font-weight-bold">
            <div class="col">Naam</div>
            <div class="col d-none d-sm-block">Alias</div>
            <div class="col d-none d-md-block">Eenheid</div>
            <div class="col">Diagram type</div>
            <div class="col-1">Kleur</div>
            <div class="col-1 text-right">
                <a href="javascript:void" data-toggle="collapse" data-target="#collapseNew" aria-expanded="true" aria-controls="collapseNew">
                    <i class="fas fa-plus-circle text-succes mx-1"></i>
                </a>
            </div>
        </div>
        <div id="collapseNew" class="collapse" aria-labelledby="headingOne" data-parent="#sensorNewAccordion">
            <div class="row mx-0 px-3">
                <form action="{{ route('sensor_types.store') }}" method="post" class="w-100 mb-3">
                    @csrf
                    <div class="row">
                        <div class="col-lg col-md col-12">
                            <label for="inputNewName" class="d-block d-md-none mt-2">Naam</label>
                            <input id="inputNewName" name="name" type="text" class="form-control" required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <label for="inputNewNameAlias" class="d-block d-md-none mt-2">Alias</label>
                            <input id="inputNewNameAlias" name="name_alias" type="text" class="form-control" required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <label for="inputNewUnit" class="d-block d-md-none mt-2">Eenheid</label>
                            <input id="inputNewUnit" name="measuring_unit" type="text" class="form-control">
                        </div>
                        <div class="col-lg col-md col-12">
                            <label for="inputNewDiagram" class="d-block d-md-none mt-2">Diagram type</label>
                            <select id="inputNewDiagram" name="graph_type" id="graph_type" class="form-control">
                                <option value="line">Lijn diagram</option>
                                <option value="bar">Staaf diagram</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-1 col-12">
                            <label for="inputNewColor" class="d-block d-md-none mt-2">Kleur</label>
                            <input id="inputNewColor" name="color" type="color" class="form-control" value="#FF0000" required>
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
        @foreach ($sensors as $key=>$sensor)
        <div class="sensor">
            <div class="row py-3 mx-0">
                <div class="col-lg col-md col">
                    <span class="font-weight-bolder">{{ $sensor->name }}</span>
                    <span class="d-block d-sm-none">{{ $sensor->name_alias }} ({{ $sensor->measuring_unit }})</span>
                </div>
                <div class="col-lg col-md col d-none d-sm-block" >
                    <span class="d-none d-md-block">{{ $sensor->name_alias }}</span>
                    <span class="d-block d-md-none">{{ $sensor->name_alias }} ({{ $sensor->measuring_unit }})</span>
                </div>
                <div class="col-lg col-md d-none d-md-block">
                    {{ $sensor->measuring_unit }}
                </div>
                <div class="col">
                    {{ $sensor->graph_type }}
                </div>
                <div class="col-sm-1 col-2">
                    <div style="background-color: {{$sensor->color}}; width: 100%; height: 20px"></div>
                </div>
                <div class="col-1 text-right">
                    <a href="javascript:void" data-toggle="collapse" data-target="#collapse{{ $sensor->id }}"
                        aria-expanded="true" aria-controls="collapse{{ $sensor->id }}">
                        <i class="fas fa-edit text-warning mx-1"></i>
                    </a>
                    <form id="form{{ $sensor->id }}" action="{{ route('sensor_types.destroy', $sensor->id) }}"
                            method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <a href="javascript:void"
                            onclick="document.getElementById('form{{ $sensor->id }}').submit();">
                            <i class='far fa-trash-alt text-danger mx-1'></i>
                        </a>
                    </form>
                </div>
            </div>
            <div id="collapse{{ $sensor->id }}" class="collapse" aria-labelledby="headingOne" data-parent="#sensorAccordion">
                <div class="row mx-0 px-3">
                    <form action="{{ route('sensor_types.update') }}" method="post" class="w-100 mb-3">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="id" value="{{ $sensor->id }}">
                        <div class="row">
                        <div class="col-lg col-md col-12">
                            <label for="inputNewName{{$key}}" class="d-block d-md-none mt-2">Naam</label>
                            <input id="inputNewName{{$key}}" name="name" type="text" value="{{ old('name') ?? $sensor->name }}"
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <label for="inputNewNameAlias{{$key}}" class="d-block d-md-none mt-2">Alias</label>
                            <input id="inputNewNameAlias{{$key}}" name="name_alias" type="text"
                                    value="{{ old('name_alias') ?? $sensor->name_alias }}"
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <label for="inputNewUnit{{$key}}" class="d-block d-md-none mt-2">Eenheid</label>
                            <input id="inputNewUnit{{$key}}" name="measuring_unit" type="text"
                                    value="{{ old('measuring_unit') ?? $sensor->measuring_unit }}"
                                    class="form-control"
                                    required>
                        </div>
                        <div class="col-lg col-md col-12">
                            <label for="inputNewDiagram{{$key}}" class="d-block d-md-none mt-2">Diagram type</label>
                            <select id="inputNewDiagram{{$key}}" name="graph_type" id="graph_type" class="form-control" required>
                                <option {{ ($sensor->graph_type == "line") ? "selected" : "" }} value="line">Lijn diagram</option>
                                <option {{ ($sensor->graph_type == "bar") ? "selected" : "" }} value="bar">Staaf diagram</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-1 col-12">
                            <label for="inputNewColor{{$key}}" class="d-block d-md-none mt-2">Kleur</label>
                            <input id="inputNewColor{{$key}}" name="color" type="color" value="{{ old('color') ?? $sensor->color }}"
                                    class="form-control"
                                    required>
                        </div>
                            <div class="col-lg-1 col-md-1 col-12 text-right">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="m-0">
        </div>
        @endforeach
    </div>
</div>
@endsection
