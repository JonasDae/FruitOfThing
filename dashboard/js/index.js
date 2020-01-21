// graph
// datasets
/*
[0]: dendro
[1]: water
[2]: temp
[3]: lucht
*/
var datasets = [];
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
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple'],
        datasets: [{
            data: [],
            borderWidth: 1,
			backgroundColor: '#ff6574',
        },
		{
            data: [],
            borderWidth: 1,
			backgroundColor: '#74ff65',
		},
		{
            data: [],
            borderWidth: 1,
			backgroundColor: '#6574ff',
		},
		{
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

// ajax
var BASE_URL = "https://floriandh.sinners.be/pcfruit/api/";
function fill_table() {
   $.ajax({	
   	url: BASE_URL + "measurement/read.php",
	dataType: 'json',
	success: function(data){
		datasets[0] = [];
		datasets[1] = [];
		datasets[2] = [];
		datasets[3] = [];

        $.each(data, function(index, element) {
			datasets[0].push(element.dendrometer);
			datasets[1].push(element.watermark);
			datasets[2].push(element.temperature);
			datasets[3].push(element.humidity);
			var content = 	"<tr>";
			content +=			"<td>";
			content +=				element.date_time;
			content +=			"</td>";
			content +=			"<td>";
			content +=				element.dendrometer;
			content +=			"</td>";
			content +=			"<td>";
			content +=				element.watermark;
			content +=			"</td>";
			content +=			"<td>";
			content +=				element.temperature;
			content +=			"</td>";
			content +=			"<td>";
			content +=				element.humidity;
			content +=			"</td>";
			content +=		"</tr>";
            $('#data_table_body').append(content);
		});
	}})
}

function fill_notifications(){
	$.ajax({	
		url: BASE_URL + "notification/read.php",
	 	dataType: 'json',
	 success: function(data){
		 $.each(data, function(index, element) {
			 var content = 	"<li>";
			 content +=			"<h3>";
			 content +=				element.title;
			 content +=			"</h3";
			 content +=			"<p>";
			 content +=				element.description;
			 content +=			"</p>";
			 content +=		"</li>";
			 $('#notificationFeed').append(content);
		 });
	 }})
}

fill_table();
fill_notifications();