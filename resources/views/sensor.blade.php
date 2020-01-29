@extends('layouts.app')

@section('content')
<div class="container">
    <row>
    <div class="col-12 content-title text-center">
        <h1>Sensoren</h1>
        <div class="headerButtons">
            <button type="button" class="btn">Toevoegen</button>
        </div>
    </div>

    <table class="col-12 table table-hover">
        <caption>Lijst van sensoren</caption>
        <thead class="">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Naam</th>
                <th scope="col">Alias</th>
                <th scope="col">Eenheid</th>
                <th scope="col" class="col-auto">Acties</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sensors as $key=>$sensor)
            <tr>
                <td scope="row">
                    {{ $key + 1 }}
                </td>
                <td>
                    {{ $sensor->name }}
                </td>
                <td>
                    {{ $sensor->name_alias }}
                </td>
                <td>
                    {{ $sensor->measuring_unit }}
                </td>
                <td>
                    <a href="#">aanpassen</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>   
    </row>
</div>
@endsection
