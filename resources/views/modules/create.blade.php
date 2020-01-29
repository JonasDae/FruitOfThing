@extends('layouts.app')

@section('content')
    <div class="container pt-3">
        <form method="POST" action="{{ route('modules.store') }}">
            @csrf

            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Naam') }}</label>
                <div class="col-md-6">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                           value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="field" class="col-md-4 col-form-label text-md-right">{{ __('Veld') }}</label>
                <div class="col-md-6">
                    <select name="field" id="field" class="form-control">
                        @foreach($fields as $field)
                            <option value="{{ $field->id }}">{{ $field->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </form>
    </div>
@endsection
