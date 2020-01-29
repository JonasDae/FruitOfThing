@extends('layouts.app')

@section('content')
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
            <div id="div_feed">
                <ol id="notificationFeed" class="list-group list-unstyled">

                </ol>
            </div>
        </div>

        <div id="div_main" class="col-md-8 col-sm-12">
            <div class="content-title text-center">
                <h5>Grafieken en statistieken</h5>
            </div>

            <div class="row">
                <div class="col-sm-6 col-lg-3 form-group">
                    <label for="slc_soort">Vruchtsoort</label>
                    <select id="slc_soort" class="form-control">
                        @foreach($fruit_types as $fruit_type)
                            <option value="{{ $fruit_type->id }}">{{ $fruit_type->name }}</option>
                            @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-lg-3 form-group">
                    <label for="slc_weergave">Weergave</label>
                    <select id="slc_weergave" class="form-control">
                        <option value="hour">uur</option>
                        <option value="day">dag</option>
                        <option value="week">week</option>
                        <option selected value="month">maand</option>
                        <option value="year">jaar</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-3 form-group">
                    <label for="dte_begin">Begindatum</label>
                    <input type="date" id="dte_begin" placeholder="Selecteer begindatum" class="form-control">
                </div>
                <div class="col-sm-6 col-lg-3 form-group">
                    <label for="dte_end">Einddatum</label>
                    <input type="date" id="dte_end" placeholder="Selecteer einddatum" class="form-control">
                </div>
                <div class="col-12">
                    <canvas id="cnv_graph" class="mb-4 mt-4"></canvas>
                </div>
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
                                <th scope="col"> Grootte</th>
                                <th scope="col"> Bodemvochtigheid</th>
                                <th scope="col"> Temperatuur</th>
                                <th scope="col"> Luchtvochtigheid</th>
                            </tr>
                            </thead>
                            <tbody id="data_table_body">
                            @foreach($measurements as $measurement)
                                <td></td>
                                <td></td>
                                <td>{{ $measurement->value }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script>
		var out = {!! json_encode($chart_data) !!};
		console.log(out);
		var cnv_graph = document.getElementById("cnv_graph").getContext("2d");
		console.log(cnv_graph);
		var chart_out = new Chart(cnv_graph, {
			type: 'bar',
			data: {
				labels: out.data.labels,
				datasets: out.data.datasets,
			},
			options: {
				title: {
					display: false,
					text: "Grafiek  titel",
					fontSize: 23,
				},
				legend: {
					display: true,
					position: 'bottom',
					fullwidth: true,
				},
				scales: {
					yAxes: [{
						id: 'axis1',
						ticks: {
							beginAtZero: true
						},
						type: 'linear',
						position: 'left',
					},
					{
						id: 'axistemp',
						type: 'linear',
						position: 'right',
					}]
				}
			}
		});

	</script>
@endsection
