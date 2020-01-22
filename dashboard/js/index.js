// graph
// datasets
/*
[0]: dendro
[1]: water
[2]: temp
[3]: lucht
*/
var datasets = [];

// init


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
			yAxisID: 'axistemp',
            data: [],
            borderWidth: 1,
			backgroundColor: '#ff6574',
			hoverBorderWidth: 3,
			hoverBorderColor: '#000000',
        },
		{
			yAxisID: 'axis1',
            data: [],
            borderWidth: 1,
			backgroundColor: '#74ff65',
			hoverBorderWidth: 3,
			hoverBorderColor: '#000000',
		},
		{
			yAxisID: 'axis1',
			type: 'line',
            data: [],
            borderWidth: 1,
			backgroundColor: '#6574ff',
			hoverBorderWidth: 3,
			hoverBorderColor: '#000000',
		},
		{
			yAxisID: 'axis1',
            data: [],
            borderWidth: 1,
			backgroundColor: '#ff74ff',
			hoverBorderWidth: 3,
			hoverBorderColor: '#000000',
		}]
    },
    options: {
		title: {
			display: true,
			text: "prototype",
			fontSize: 23,
		},
		legend: {
			display: false
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

// ajax
var BASE_URL = "https://floriandh.sinners.be/pcfruit/";
var URL_MEASURE = BASE_URL + "api/measurement/read.php";
var URL_FRUIT_TYPE = BASE_URL + "api/fruit_type/read.php";
var URL_NOTIFICATION = BASE_URL + "api/fruit_type/read.php";

function fill_table() {
   $.ajax({	
   	url: URL_MEASURE,
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
		url: URL_NOTIFICATION,
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

function fill_select_soort() {
   $.ajax({	
   	url: URL_FRUIT_TYPE,
	dataType: 'json',
	success: function(data){

        $.each(data, function(index, element) {
			var content = 	"<option";
			content +=			" value=\""+element.id+"\"";
			content +=			">";
			content +=			element.name;
			content +=		"</option>";
            $('#slc_soort').append(content);
		});
	}})
}

fill_table();
fill_select_soort();
fill_notifications();


