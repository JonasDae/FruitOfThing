<?php

namespace App\Http\Controllers;

use App\Fruit_type;
use App\Measurement;
use App\Sensor;
use App\Module_sensor;
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
		$out->fill = $color;
		$out->hoverBorderWidth = 3;
		$out->hoverBorderColor = $color;
		$out->order = $order;
		return $out;
	}
	private function chart_build()
	{
        $measurements = Measurement::get();

		$out = new stdClass();
		$out->type = 'bar';
		$out->data = new stdClass();
		$out->data->labels = ["ASD", "BCD", "DJKLD"]; 
		$out->data->datasets = [];

		$measures = Measurement::get();
		$out->labels = [];

		$sensor_data = [];
		$sensor_names = [];

		for($i = 0; $i < count($measures); $i++)
		{
			if(!in_array($measures[$i]->measure_date, $out->labels))
				array_push($out->labels, $measures[$i]->measure_date);
			if(!in_array($measures[$i]->module_sensor->sensor->name_alias,$sensor_names))
				$sensor_names[$measures[$i]->module_sensor->sensor_id] = $measures[$i]->module_sensor->sensor->name_alias;

// per date per sensor
			if(!isset($sensor_data[$measures[$i]->measure_date] ))
				$sensor_data[$measures[$i]->measure_date] = [];
			if(!isset($sensor_data[$measures[$i]->measure_date][$measures[$i]->module_sensor->sensor->name_alias] ))
				$sensor_data[$measures[$i]->measure_date][$measures[$i]->module_sensor->sensor->name_alias] = [];
			array_push($sensor_data[$measures[$i]->measure_date][$measures[$i]->module_sensor->sensor->name_alias], $measures[$i]->value);

// per sensor per date
/*
			if(!isset($sensor_data[$measures[$i]->module_sensor->sensor->name_alias] ))
				$sensor_data[$measures[$i]->module_sensor->sensor->name_alias] = [];

			if(!isset($sensor_data[$measures[$i]->module_sensor->sensor->name_alias][$measures[$i]->measure_date] ))
				$sensor_data[$measures[$i]->module_sensor->sensor->name_alias][$measures[$i]->measure_date] = [];

			array_push($sensor_data[$measures[$i]->module_sensor->sensor->name_alias][$measures[$i]->measure_date], $measures[$i]->value );
*/
		}
		$data = [];
		foreach($sensor_names as $s)
			$data[$s] = [];
		foreach($sensor_data as $sensor_date => $val_by_sensor)
		{
			
			dd($sensor_data);
			$dates[$i] = $sensor_date;

			foreach($val_by_sensor as $sensor_name => $sensor_val)
			{
		dd($val_by_sensor);
				array_push($data[$i], $sensor_val);
			}

			
		}
		for($i = 0; $i < count($data); $i++)
			$out->data->datasets[$i] = $this->chart_dataset($names[$i], "axis1", "bar", "#FF00FF", 3, $data[$i]);

		$i = 0;
		foreach($sensor_data as $sensorname => $val)
		{
			$data = [];

			foreach($val as $key2 => $val2)
			{
				foreach($val2 as $key3 => $val3)
				{
					array_push($data, $val3);
				}
			}

			$out->data->datasets[$i] = $this->chart_dataset($sensorname, "axis1", "bar", "#FF00FF", 3, $data);
			$i++;
		}

		return $out;
	}
    public function index()
    {
        //get measurements & fruit_types
        $measurements = Measurement::get();
        $fruit_types = Fruit_type::get();

        return view('home', array(
            'measurements' => $measurements,
            'fruit_types' => $fruit_types,
            'chart_data' => $this->chart_build(),
        ));
    }
}
