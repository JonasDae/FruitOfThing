@extends('layouts.app')

@section('content')
    <div class="container pt-3">
        <h1 class="text-center">Mijn profiel</h1>
        <div class="row align-items-center">
            <div class="col-lg-4 col-md-6 col-12 text-md-right text-center">
                <i class="fas fa-user-circle h-75 w-75"></i>
            </div>
            <div class="col-lg-8 col-md-6 col-12">
                <form action="{{ route('profile.update', $profile->id) }}" method="post" class="p-3">
                    @method('PATCH') {{--PATCH to update an existing source (method="patch" won't work so we do it this way)--}}
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label">Naam</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') ?? $profile->name }}" autocomplete="name" autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label">E-Mail</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ $profile->email }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-10 d-flex justify-content-end">
                            <button type="submit" class="btn btn-secondary r-0">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <h1 class="mt-5 text-md-left text-center">Andere profielen</h1>
        <div class="row mt-3 font-weight-bold d-none d-md-flex">
            <div class="col-lg-4 col-md-4">Naam</div>
            <div class="col-lg-6 col-md-4">Email</div>
            <div class="col-lg-1 col-md-2 text-md-center text-right">Toegang</div>
            <div class="col-lg-1 col-md-2 text-md-center text-right">Geverifieerd</div>
        </div>
        <hr>
        @foreach ($users as $key => $user)
            @if ($user->email !== $profile->email)
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-auto">
                        {{ $user->name }}
                    </div>
                    <div class="col-lg-6 col-md-4 col">
                        {{ $user->email }}
                    </div>
                    <div class="col-lg-1 col-md-2 col-auto text-md-center text-right">
                        <form id="access{{ $key }}" action="{{ route('profile.update', $user->id) }}" method="post">
                            @method('PATCH')
                            @csrf
                            <div class="m-0 p-0 no-gutters" data-toggle="popover" data-html="true" data-trigger="hover" data-content="Toegang geven is vergrendeld wanneer deze gebruiker niet geverifieerd is.">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="access" class="custom-control-input" id="customSwitchDis{{ $key }}"{{ $user->access ? ' checked' : '' }} {{ empty($user->email_verified_at) ? 'disabled' : 'onchange=this.form.submit()' }}>
                                    <label class="custom-control-label" for="customSwitchDis{{ $key }}"></label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-1 col-md-2 col-auto text-md-center text-right">
                        <i class="fas {{ empty($user->email_verified_at) ? 'fa-times text-danger' : 'fa-check text-success' }}"></i>
                    </div>
                </div>
                <hr>
            @endif
        @endforeach
    </div>
@endsection
