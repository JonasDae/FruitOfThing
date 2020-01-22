// defines
const DATASET_DENDRO 	= 0;
const DATASET_WATER 	= 1;
const DATASET_TEMP 		= 2;
const DATASET_LUCHT 	= 3;

const GRAPH_COLOR_DENDRO 	= '#00ff00';
const GRAPH_COLOR_WATER		= '#0000ff';
const GRAPH_COLOR_TEMP		= '#ff0000';
const GRAPH_COLOR_LUCHT 	= 'orange';

const GRAPH_TYPE_DENDRO 	= 'line';
const GRAPH_TYPE_WATER		= 'bar';
const GRAPH_TYPE_TEMP		= 'line';
const GRAPH_TYPE_LUCHT 		= 'bar';

const FLAG_NUM_FLAGS	= 4;

const FLAG_SHOW_DENDRO 	= 1;
const FLAG_SHOW_WATER 	= 2;
const FLAG_SHOW_TEMP 	= 4;
const FLAG_SHOW_LUCHT 	= 8;


// global vars
var graph_select_flags = 0;
var data_measure = [];

function data_lowest_week() {
	var out = data_measure[0].date_time;
	$.each(data_measure, function(index, element) {
		if(element.date_time < out) {
			out = element.date_time;
		}
	})
}
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
	fetch_data_measure_by_type(value);
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
        labels: ['Januari', 'Februari', 'Maart', 'April', 'Mei'],
        datasets: [{
			yAxisID: 'axistemp',
            data: [],
			type: GRAPH_TYPE_DENDRO,
			borderColor: GRAPH_COLOR_DENDRO,
			fill: false,
			hoverBorderWidth: 3,
			hoverBorderColor: '#000000',
			order: 1,
        },
		{
			yAxisID: 'axis1',
            data: [],
			type: GRAPH_TYPE_WATER,
			backgroundColor: GRAPH_COLOR_WATER,
			hoverBorderWidth: 3,
			hoverBorderColor: '#000000',
			order: 3,
		},
		{
			yAxisID: 'axis1',
			type: 'line',
            data: [],
			type: GRAPH_TYPE_TEMP,
			borderColor: GRAPH_COLOR_TEMP,
			fill: false,
			hoverBorderWidth: 3,
			hoverBorderColor: '#000000',
			order: 2,
		},
		{
			yAxisID: 'axis1',
            data: [],
			type: GRAPH_TYPE_LUCHT,
			backgroundColor: GRAPH_COLOR_LUCHT,
			hoverBorderWidth: 3,
			hoverBorderColor: '#000000',
			order: 4,
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

function fetch_data_measure_by_type(typeid) {
   $.ajax({	
   	url: URL_MEASURE_TYPE+typeid,
	dataType: 'json',
	success: function(data){
		data_measure = [];
        $.each(data, function(index, element) {
			var date = element.date_time;
			element.date_time = new Date(Date.parse(date));
			data_measure.push(element);
		});
		graph_fill_by_flags();
		table_fill();
	}})
}
function fetch_data_measure() {
   $.ajax({	
   	url: URL_MEASURE,
	dataType: 'json',
	success: function(data){
		data_measure = [];
        $.each(data, function(index, element) {
			var date = element.date_time;
			element.date_time = new Date(Date.parse(date));
			data_measure.push(element);
		});
		graph_fill_by_flags();
		table_fill();
	}})
}

function table_fill() {
	$('#data_table_body').empty();
	$.each(data_measure, function(index, element) {
		var content = 	"<tr>";
		content +=			"<td>";
		content +=				element.date_time != null ? element.date_time : "";
		content +=			"</td>";
		content +=			"<td>";
		content +=				element.dendrometer != null ? element.dendrometer : "";
		content +=			"</td>";
		content +=			"<td>";
		content +=				element.watermark != null ? element.watermark : "";
		content +=			"</td>";
		content +=			"<td>";
		content +=				element.temperature != null ? element.temperature : "";
		content +=			"</td>";
		content +=			"<td>";
		content +=				element.humidity != null ? element.humidity : "";
		content +=			"</td>";
		content +=		"</tr>";
		$('#data_table_body').append(content);
	})
}
function fill_notifications() {
    $.ajax({
        url: URL_NOTIFICATION,
        dataType: 'json',
        success: function (data) {
            $.each(data, function (index, element) {
            	var bg = element.severity == "alert" ? "rgba(220, 53, 69, 0.5)" : "rgba(255, 193, 7, 0.5)";
            	var text = element.severity == "alert" ? "text-light" : "text-muted";
                var content = "<li class='mb-1' style='background-color: " + bg + "'>";
                content += "<div class='row ml-2'>";
                content += "<div class='col-auto'>";
				content += "<div class='row'>";
                content += "<h4 class='mb-0'>" + element.title + "</h4>";
                content += "</div>";
				content += "<div class='row'>";
				content += "<span class='" + text + "'>" + element.description + "</span>";
				content += "</div>";
				content += "</div>";
				content += "<div class='col-auto'>";
                content += "<p>" + element.date_time + "</p>";
				content += "</div>";
				content += "</div>";
                content += "</li>";
                $('#notificationFeed').append(content);
            });
        }
    })
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

function ui_init() {
// span colors
	$('#chk_span_1').css('background-color', GRAPH_COLOR_DENDRO);
	$('#chk_span_1').html(GRAPH_COLOR_DENDRO);
	$('#chk_span_2').css('background-color', GRAPH_COLOR_WATER);
	$('#chk_span_2').html(GRAPH_COLOR_WATER);
	$('#chk_span_4').css('background-color', GRAPH_COLOR_TEMP);
	$('#chk_span_4').html(GRAPH_COLOR_TEMP);
	$('#chk_span_8').css('background-color', GRAPH_COLOR_LUCHT);
	$('#chk_span_8').html(GRAPH_COLOR_LUCHT);
// checkboxes
	$('.chk_dataset').each(function(i, obj) {
		if(obj.checked) {
			graph_select_flags |= obj.value;
		}
		else {
			graph_select_flags &= ~obj.value;
		}
	});
	graph_fill_by_flags();
}

fetch_data_measure();
fill_select_soort();
fill_notifications();
ui_init();
