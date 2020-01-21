// graph
// datasets
var datasets = [[12, 19, 3, 5, 2, 3],
				[11, 18, 5, 2, 12, 13],
				[1, 8, 15, 8, 5, 8]];
// checkboxes
$('input[class=chk_dataset]').change(function() {
	var value = $(this).val();
	if($(this).is(':checked')) {
		chart_out.data.datasets[value].data = datasets[value];
		chart_out.update();
	}
	else {
		chart_out.data.datasets[value].data = [];
		chart_out.update();
	}
});
// canvas
var cnv_graph = document.getElementById("cnv_graph").getContext("2d");

// init chart
var chart_out = new Chart(cnv_graph, {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: 'set 0',
            data: [],
            borderWidth: 1,
			backgroundColor: '#ff6574',
        },
		{
            label: 'set 1',
            data: [],
            borderWidth: 1,
			backgroundColor: '#74ff65',
		},
		{
            label: 'set 2',
            data: [],
            borderWidth: 1,
			backgroundColor: '#6574ff',
		}]
    },
    options: {
		legend: {
			display: false
		},
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
