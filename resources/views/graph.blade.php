<script src="{{ asset('js/chart.min.js') }}"></script>
<canvas id="myChart"></canvas>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', //required parameter
        data: <?php echo str_replace(array("\"'", "'\""), array('', ''), json_encode($graph_data->toArray())) ?>, {{--I use the standard php echo code because the laravel code echoes encoded htmlentities--}}
        options: {
            legend: {
                display: true,
                position: 'bottom',
                fullwidth: true,
            },
            scales: {
                yAxes: [{
                    id: 'axisleft',
                    ticks: {
                        beginAtZero: true
                    },
                    type: 'linear',
                    position: 'left',
                },
                ]
            }
        }
    });
</script>
