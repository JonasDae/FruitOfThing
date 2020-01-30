@extends('layouts.app')

@section('content')
    <div class="container pt-3">
        <div id="NewAccordion">
            <div class="row mt-3 mx-0 font-weight-bold d-none d-md-flex">
                <div class="col-lg-2 col-md-3">Naam</div>
                <div class="col-lg-2 col-md-3">Fruitsoort <a href="{{ route('fruits.index') }}"><i
                            class="fas fa-external-link-alt"></i></a></div>
                <div class="col-lg-6 col-md-4">Adres</div>
                <div class="col-lg-1 col-md-1 text-right">Postcode</div>
                <div class="col-1 text-right">
                    <a href="javascript:void" data-toggle="collapse" data-target="#collapseNew" aria-expanded="true"
                       aria-controls="collapseNew"><i class="fas fa-plus-circle text-success mx-1"></i></a>
                </div>
            </div>

            {{--Add new field Accordion--}}
            <div id="collapseNew" class="collapse" aria-labelledby="headingOne" data-parent="#NewAccordion">
                <div class="row mx-0 px-3">
                    <form action="{{ route('fields.store') }}" method="post" class="w-100 mt-2">
                        @csrf
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-12">
                                <input id="name" name="name" type="text" class="form-control" required>
                            </div>
                            <div class="col-lg-2 col-md-3 col-12">
                                <select name="fruit_type" id="fruit_type" class="form-control">
                                    @foreach($fruit_types as $fruit_type)
                                        <option value="{{ $fruit_type->id }}">{{ $fruit_type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-4 col-10">
                                <input id="adres" name="adres" type="text" class="form-control" required>
                            </div>
                            <div class="col-lg-1 col-md-1 col-2 text-right">
                                <input id="postcode" name="postcode" type="number" class="form-control" required>
                            </div>
                            <div class="col-lg-1 col-md-1 col text-right">
                                <button type="submit" class="btn btn-success">Opslaan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <hr class="mb-0">
        </div>

        <div id="fieldAccordion">
            @foreach ($fields as $field)
                <div class="record">
                    {{--Rows--}}
                    <div class="row py-3 mx-0">
                        <div class="col-lg-2 col-md-3 col-12">
                            <span class="font-weight-bolder">{{ $field->name }}</span>
                        </div>
                        <div class="col-lg-2 col-md-3 col-12">
                            {{ $field->fruit_type->name }}
                        </div>
                        <div class="col-lg-6 col-md-4 col-10">
                            {{ $field->adres }}
                        </div>
                        <div class="col-lg-1 col-md-1 col-2 text-right">
                            {{ $field->postcode }}
                        </div>
                        {{--CRUD--}}
                        <div class="col-lg-1 col-md-1 col-6">
                            <a href="javascript:void" data-toggle="collapse" data-target="#collapse{{ $field->id }}"
                               aria-expanded="true" aria-controls="collapse{{ $field->id }}"><i
                                    class="fas fa-edit text-warning mx-1"></i></a>
                            <form id="form{{ $field->id }}" action="{{ route('fields.destroy', $field->id) }}"
                                  method="post"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <a href="javascript:void"
                                   onclick="document.getElementById('form{{ $field->id }}').submit();""><i
                                    class='far fa-trash-alt text-danger mx-1'></i></a>
                            </form>
                        </div>
                    </div>
                    {{--Accordions--}}
                    <div id="collapse{{ $field->id }}" class="collapse" aria-labelledby="headingOne"
                         data-parent="#fieldAccordion">
                        <div class="row mx-0 px-3">
                            <form action="{{ route('fields.update') }}" method="post" class="w-100 mb-3">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $field->id }}">
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-12">
                                        <input id="name" name="name" type="text"
                                               value="{{ old('name') ?? $field->name }}"
                                               class="form-control"
                                               required>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-12">
                                        <select name="fruit_type" id="fruit_type" class="form-control">
                                            @foreach($fruit_types as $fruit_type)
                                                <option value="{{ $fruit_type->id }}">{{ $fruit_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-4 col-10">
                                        <input id="adres" name="adres" type="text"
                                               value="{{ old('adres') ?? $field->adres }}"
                                               class="form-control"
                                               required>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-2 text-right">
                                        <input id="postcode" name="postcode" type="number"
                                               value="{{ old('postcode') ?? $field->postcode }}"
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
                </div>
                <hr class="m-0">
            @endforeach
        </div>
    </div>
@endsection
