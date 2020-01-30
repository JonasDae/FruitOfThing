@extends('layouts.app')

@section('content')
    <div class="container pt-3">
        <div class="row mt-3 mx-0 font-weight-bold d-none d-md-flex">
            <div class="col-lg-12 col-md-12">Naam <a href="{{ route('fruits.create') }}"><i class="fas fa-plus-circle text-success"></i></a></div>
        </div>
        <hr class="mb-0">

        <div id="moduleAccordion">
            @foreach ($fruit_types as $fruit_type)
                <div class="record">
                    {{--Rows--}}
                    <div class="row py-3 mx-0">
                        <div class="col">
                            <span class="font-weight-bolder">{{ $fruit_type->name }}</span>
                        </div>
                        <div class="col-auto">
                            <a href="javascript:void" data-toggle="collapse" data-target="#collapse{{ $fruit_type->id }}"
                               aria-expanded="true" aria-controls="collapse{{ $fruit_type->id }}"><i
                                    class="fas fa-edit text-warning mx-1"></i></a>
                            {{--CRUD--}}
                            <form id="form{{ $fruit_type->id }}" action="{{ route('fruits.destroy', $fruit_type->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <a href="javascript:void" onclick="document.getElementById('form{{ $fruit_type->id }}').submit();""><i class='far fa-trash-alt text-danger mx-1'></i></a>
                            </form>
                        </div>
                    </div>
                    {{--Accordions--}}
                    <div id="collapse{{ $fruit_type->id }}" class="collapse" aria-labelledby="headingOne"
                         data-parent="#moduleAccordion">
                        <div class="row mx-0 px-3">
                            <form action="{{ route('fruits.update') }}" method="post" class="w-100 mb-3">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $fruit_type->id }}">
                                <div class="row">
                                    <div class="col-auto">
                                        <input name="name" type="text" value="{{ old('name') ?? $fruit_type->name }}"
                                               class="form-control"
                                               required>
                                    </div>
                                    <div class="col">
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
