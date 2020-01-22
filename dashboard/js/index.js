// defines
const DATASET_DENDRO 	= 0;
const DATASET_WATER 	= 1;
const DATASET_TEMP 		= 2;
const DATASET_LUCHT 	= 3;

const FLAG_NUM_FLAGS	= 4;

const FLAG_SHOW_DENDRO 	= 1;
const FLAG_SHOW_WATER 	= 2;
const FLAG_SHOW_TEMP 	= 4;
const FLAG_SHOW_LUCHT 	= 8;


// global vars
var graph_select_flags = 0;
var data_measure = [];

// graph data control
function graph_fill_by_flags() {
	for(var i=0;i<FLAG_NUM_FLAGS;i++) {
		if(graph_select_flags & 1<<i) {
			graph_set_dataset(i);
		}
		else {
			graph_clr_dataset(i);
		}
	}
}
function graph_set_dataset(setnr) {
	var data = [];
	 $.each(data_measure, function(index, element) {
	 	var val;
		switch(setnr) {
			case DATASET_DENDRO:
				val = element.dendrometer;
				break;
			case DATASET_WATER:
				val = element.watermark;
				break;
			case DATASET_TEMP:
				val = element.temperature;
				break;
			case DATASET_LUCHT:
				val = element.humidity;
				break;
		}
	 	data.push(val);
	 });
	chart_out.data.datasets[setnr].data = data;
	chart_out.update();
}
function graph_clr_dataset(setnr) {
	chart_out.data.datasets[setnr].data = [];
	chart_out.update();
}

// ui interaction
// dropdowns
$('select[id=slc_soort]').change(function() {
	var value = $(this).val();
	fill_data_measure(value);
});
// checkboxes
$('input[class=chk_dataset]').change(function() {
	var value = $(this).val();
	if($(this).is(':checked')) {
		graph_select_flags |= value;
	}
	else {
		graph_select_flags &= ~value;
	}
	graph_fill_by_flags();
});


// init chart
var cnv_graph = document.getElementById("cnv_graph").getContext("2d");
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
var URL_MEASURE_TYPE = BASE_URL + "api/measurement/readByType.php?id=";
var URL_FRUIT_TYPE = BASE_URL + "api/fruit_type/read.php";
var URL_NOTIFICATION = BASE_URL + "api/notification/read.php";

function fill_data_measure(typeid) {
   $.ajax({	
   	url: URL_MEASURE_TYPE+typeid,
	dataType: 'json',
	success: function(data){
		data_measure = [];
        $.each(data, function(index, element) {
			data_measure.push(element);
		});
		graph_fill_by_flags();
	}})
}

function fill_table() {
   $.ajax({	
	url: URL_MEASURE,
	dataType: 'json',
	success: function(data){

        $.each(data, function(index, element) {
			data_measure.push(element);
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
		graph_fill_by_flags();
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
