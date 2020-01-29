<?php

namespace App\Http\Controllers;

use App\Fruit_type;
use App\Measurement;
use App\Sensor;
use App\Module_sensor;
use App\Notification;
use App\Module;
use Illuminate\Http\Request;
use stdClass;



class HomeController extends Controller
{
	private function chart_dataset($label, $axis, $type, $color, $order, $data)
	{
		$out = new stdClass();
		$out->yAxisID = $axis;
		$out->label = $label;
		$out->data = $data;
		$out->type = $type;
		$out->borderColor = $color;
		$out->backgroundColor = $color;
		$out->hoverBorderWidth = 3;
		$out->hoverBorderColor = $color;
		$out->order = $order;
		return $out;
	}
	private function sort_measurements($measurements, $fruittype, $date_display)
	{
		$date_format = [
				"Y/m/d H:00",
				"Y/m/d",
				"Y \w\k W",
				"Y/m",
				"Y",
			];
		$out = [];

		for($i=0; $i < count($measurements); $i++)
		{
			if(		$measurements[$i]->module->field->fruit_type->id == $fruittype)
			/*
			   && 	$measurements[$i]->measure_date < enddate
			   && 	$measurements[$i]->measure_date > $startdate)
			)
			*/
			{
				$date = new \DateTime($measurements[$i]->measure_date);
				$measurements[$i]->measure_date = $date->format($date_format[$date_display]);
				array_push($out, $measurements[$i]);
			}
		}
		return $out;
	}
	public function chart_build($fruittype, $date_display)
	{
		$graph_colors = [
				"#FF0000",
				"#00FF00",
				"#0000FF",
				"#FFFF00",
				"#00FFFF",
				"#FAFAFA",
			];

        $sensors = Sensor::get();
		$measurements = Measurement::get();
		$measurements = $this->sort_measurements($measurements, $fruittype, $date_display);

		$out = new stdClass();
		$out->type = 'bar';
		$out->data = new stdClass();

		$out->data->datasets = [];
		$out->labels = [];

		$sensor_data = [];
		$sensor_date = [];

		for($i = 0; $i < count($sensors); $i++)
		{
			$sensor_data[$sensors[$i]->name_alias] = [];;
		}

		for($i = 0; $i < count($measurements); $i++)
		{
			if(!in_array($measurements[$i]->measure_date, $sensor_date))
				array_push($sensor_date,$measurements[$i]->measure_date);
			foreach($sensor_data as $sensor_name => $emptyarr)
			{
				if(!isset($sensor_data[$sensor_name][$measurements[$i]->measure_date] ))
					$sensor_data[$sensor_name][$measurements[$i]->measure_date] = [];
			}
			array_push($sensor_data[$measurements[$i]->module_sensor->sensor->name_alias][$measurements[$i]->measure_date], $measurements[$i]->value);

		}
		$i = 0;
		foreach($sensor_data as $sensor_name => $data_by_date)
		{
			$avg_per_date = [];
			$avg = [];
			$j = 0;
			$data_out = [];
			foreach($data_by_date as $data_date => $datapoints)
			{
				if(count($datapoints))
					$avg[$j] = array_sum($datapoints)/count($datapoints);
				else
					$avg[$j] = null;
				$data_out[$j] = $avg[$j];
				$j++;

			}
			$out->data->datasets[$i] = $this->chart_dataset($sensor_name, "axisleft", "bar", $graph_colors[$i], 3, $data_out);
			$i++;
		}
		$out->data->labels = $sensor_date;


		return response()->json($out);
	}
    public function index()
    {
        //get measurements & fruit_types & notifications
        $notifications = Notification::get()->sortByDesc('send_date');
        $measurements = Measurement::get()->sortByDesc('measure_date');
        $fruit_types = Fruit_type::get();


        return view('home', array(
            'notifications' => $notifications,
            'measurements' => $measurements,
            'fruit_types' => $fruit_types,
//            'chart_data' => $this->chart_build(-1),
        ));
    }
}
