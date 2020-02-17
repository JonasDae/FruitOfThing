@extends('layouts.app')

@section('content')
    {{-- List view --}}
    <div id="list" class="container mt-3">
        <div id="NewAccordion">
            <div class="row mt-3 mx-0 font-weight-bold">
                <div class="col-lg-2 col-md-2 d-none d-md-block">Naam</div>
                <div class="col-lg-2 col-md-2 d-none d-md-block">Fruitsoort <a href="{{ route('fruits.index') }}"><i class="fas fa-external-link-alt"></i></a></div>
                <div class="col-lg-6 col-md-4 d-none d-md-block">Adres</div>
                <div class="col-lg-1 col-md-2 d-none d-md-block text-right">Postcode</div>
                <div class="col-lg-1 col-md-2 col text-right">
                    <a data-toggle="collapse" data-target="#collapseNew" aria-expanded="true" aria-controls="collapseNew"><span class="d-md-none">Nieuw </span><i class="fas fa-plus-circle text-success mx-1"></i></a>
                </div>
            </div>

            {{--Add new field Accordion--}}
            <div id="collapseNew" class="collapse" aria-labelledby="headingOne" data-parent="#NewAccordion">
                <div class="row mx-0 px-3">
                    <form action="{{ route('fields.store') }}" method="post" class="w-100 mt-2">
                        @csrf
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-12">
                                <input id="name" name="name" type="text" class="form-control" placeholder="Naam" required>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <select name="fruit_type" id="fruit_type" class="form-control">
                                    @foreach($fruit_types as $fruit_type)
                                        <option value="{{ $fruit_type->id }}">{{ $fruit_type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-5 col-md-4 col-8">
                                <input id="adres" name="adres" type="text" class="form-control" placeholder="Adres" required>
                            </div>
                            <div class="col-lg-2 col-md-2 col-4 text-right">
                                <input id="postcode" name="postcode" type="number" class="form-control" placeholder="Postcode" required>
                            </div>
                            <div class="col-lg-1 col-md-2 col text-right">
                                <button type="submit" class="btn btn-success">Opslaan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <hr class="mb-0">
        </div>

        <div id="fieldAccordion">
            @foreach ($fields as $key=>$field)
                <div class="record">
                    {{--Rows--}}
                    <div class="row py-3 mx-0">
                        <div class="col-lg-2 col-md-2 col-12">
                            <a href="{{ route('modules.index') }}?field_id={{ $field->id }}" class="font-weight-bolder">{{ $field->name }}</a>
                        </div>
                        <div class="col-lg-2 col-md-2 col-12">
                            {{ $field->fruit_type->name ?? '' }}
                        </div>
                        <div class="col-lg-6 col-md-4 col-10 adres">
                            {{ $field->adres }}
                        </div>
                        <div class="col-lg-1 col-md-2 col-2 text-right">
                            {{ $field->postcode }}
                        </div>
                        {{--CRUD--}}
                        <div class="col-lg-1 col-md-2 col-12 text-right">
                            <a href="javascript:void" data-toggle="collapse" data-target="#collapse{{ $field->id }}" aria-expanded="true" aria-controls="collapse{{ $field->id }}"><i class="fas fa-edit text-warning mx-1"></i></a>
                            <form id="form{{ $field->id }}" action="{{ route('fields.destroy', $field->id) }}"
                                  method="post"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <a href="javascript:void"
                                   onclick="confirm('\'{{ $field->name }}\' verwijderen?') ? document.getElementById('form{{ $field->id }}').submit() : ''"><i class='far fa-trash-alt text-danger mx-1'></i></a>
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
                                    <div class="col-lg-2 col-md-2 col-12">
                                        <input id="name{{$key}}" name="name" type="text"
                                               value="{{ old('name') ?? $field->name }}"
                                               class="form-control"
                                               required>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-12">
                                        <select name="fruit_type" id="fruit_type{{$key}}" class="form-control">
                                            @foreach($fruit_types as $fruit_type)
                                                <option
                                                    value="{{ $fruit_type->id }}" {{ $field->fruit_type_id == $fruit_type->id ? 'selected' : '' }}>{{ $fruit_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-5 col-md-4 col-8">
                                        <input id="adres{{$key}}" name="adres" type="text"
                                               value="{{ old('adres') ?? $field->adres }}"
                                               class="form-control"
                                               required>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-4 text-right">
                                        <input id="postcode{{$key}}" name="postcode" type="number"
                                               value="{{ old('postcode') ?? $field->postcode }}"
                                               class="form-control"
                                               required>
                                    </div>
                                    <div class="col-lg-1 col-md-2 col text-right">
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
    {{-- Map --}}
    <div id="map" class="container my-3">
        <iframe class="h-100" frameborder="0"></iframe>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('.record').click(function () {
                var q = encodeURIComponent($(this).find('div.adres').text().trim());
                $('#map iframe').attr('src', 'https://maps.google.com/maps?q=' + q + '&output=embed');
                document.getElementById('map').scrollIntoView(true);
            });
        });
    </script>
@endsection
