@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="div_side" class="col-md-4 d-none d-md-block">
                <div class="content-title text-center">
                    <h5>Weer &amp; temperatuur vandaag</h5>
                </div>
                <div id="weather-info">
                    <div id="buienradar-info">
                        <a href="http://www.buienradar.be" target="_blank">
                            <img src="http://api.buienradar.nl/image/1.0/radarmapbe" class="img-fluid w-100">
                        </a>
                    </div>
                    <div id="kmi-info">
                        <iframe
                            src="https://www.meteo.be/services/widget/?postcode=3800&nbDay=2&type=4&lang=nl&bgImageId=1&bgColor=567cd2&scrolChoice=0&colorTempMax=A5D6FF&colorTempMin=ffffff"></iframe>
                    </div>
                </div>
                <div class="content-title text-center">
                    <h5>Meldingen</h5>
                </div>
                <div class="card p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <li class="notification-item list-group-item severity-{{$notification->severity}}">
                                <div class="card-body p-0">
                                    <h5 class="card-title">{{$notification->title}}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{$notification->send_date}}</h6>
                                    <p class="card-text">{{$notification->description}}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div id="div_main" class="col-md-8 col-sm-12">
                <div class="content-title text-center">
                    <h5>Grafieken en statistieken</h5>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="fruit_type">Vruchtsoort</label>
                        <select id="fruit_type" class="form-control">
                            @foreach($fruit_types as $fruit_type)
                                <option value="{{ $fruit_type->id }}">{{ $fruit_type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="display">Weergave</label>
                        <select id="display" class="form-control">
                            <option value="H">uur</option>
                            <option value="d">dag</option>
                            <option value="W">week</option>
                            <option selected value="m">maand</option>
                            <option value="Y">jaar</option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="start_date">Begindatum</label>
                        <input type="date" id="start_date" placeholder="Selecteer begindatum" value="1970-01-01" class="form-control">
                    </div>
                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="end_date">Einddatum</label>
                        <input type="date" id="end_date" placeholder="Selecteer einddatum" value="2025-01-01" class="form-control">
                    </div>
                    <!--Graph-->
                    <div id="graph" class="col-12"></div>
                </div>

                <div class="content-title text-center">
                    <h5>Overzicht metingen</h5>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table" id="data_table">
                                <thead>
                                <tr>
                                    <th scope="col" width=auto> Datum</th>
                                    <th scope="col"> Module</th>
                                    <th scope="col"> Type</th>
                                    <th scope="col"> Waarde</th>
                                    <th scope="col"> Huidige vruchtgrootte</th>
                                </tr>
                                </thead>
                                <tbody id="data_table_body">
                                @foreach($measurements as $measurement)
                                    <tr>
                                        <td>{{ $measurement->measure_date }}</td>
                                        <td>{{ $measurement->module_id }}</td>
                                        <td>{{ $measurement->module_sensor->sensor->name_alias }}</td>
                                        <td>{{ $measurement->value }} {{$measurement->module_sensor->sensor->measuring_unit }}</td>
                                        <td>@if ($measurement->module_sensor->sensor->name == "Dendrometer")
                                                @foreach($sensor_added_values as $sensor_added_value)
                                                    {{ $sensor_added_value->module_sensor_id == $measurement->module_sensor_id ? $measurement->value + $sensor_added_value->value : "" }}
                                                @endforeach
                                                {{ $measurement->module_sensor->sensor->measuring_unit }}
                                            @endif</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function update_graph() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: 'get',
                url: '{{ route('graph.index') }}',
                data: {fruit_type: $('#fruit_type').val(), display: $('#display').val(), start_date: $('#start_date').val(), end_date: $('#end_date').val()},
                success: function (response) {
                    $("#graph").html(response);
                }
            });
        }

        $(document).ready(function () {
            //init
            update_graph();

            //onChange
            $("select, input[type='date']").change(function () {
                update_graph();
            });
        });
    </script>
@endsection
