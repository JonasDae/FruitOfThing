<?php

namespace App\Http\Controllers;

use App\Measurement;
use App\Sensor;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $request)
    {
        $fruit_type = $request->get('fruit_type');

        $table_data = collect($this->table($fruit_type); //can't send array so send collection instead
        return view('home', compact('table_data'));
    }

    public function table($fruit_type)
    {
        //Get measurements and filter by date
        $measurements = Measurement::get()->sortByDesc('measure_date')->values(); //->values() resets the key values after sort
        $sensors = Sensor::get();

        $data = array( //embed key's in single quotes in order to be able to replace them into the javascript code at graph.blade.php
            '\'labels\'' => array(), //1 label for each display window
            '\'datasets\'' => array(), //1 dataset for each sensor type
        );
		$collumns = array();
		$data_by_date = array();
		$out = array();

        // data by date
        foreach ($measurements as $measurement) {
            if ($measurement->module->field->fruit_type_id == $fruit_type) { //Check for fruit_type
                if (empty($data_by_date[$measurement->measure_date])) {
					$data_by_date[$measurement->measure_date] = array();
                }
                if (empty($data_by_date[$measurement->measure_date][$measurement->module_id])) {
					$data_by_date[$measurement->measure_date][$measurement->module_id] = array();
                }
				array_push($data_by_date[$measurement->measure_date][$measurement->module_id], $measurement);

                //collumn labels
                if (!in_array($measurement->module_sensor->sensor->name_alias, $collumns))
					array_push($collumns, $measurement->module_sensor->sensor->name_alias);
            }
        }

		foreach($data_by_date as $curdate) {
			$outdata = object();
			$outdata->measurement = array();
			foreach($curdate as $curmodule) {
				$outdata->measure_date = $curmodule->measure_date;
				$outdata->module_id = $curmodule->module_id;
				if(empty($outdata->measurement[$curmodule->module_sensor_id]))
					$outdata->measurement[$curmodule->module_sensor_id] = object();
				$outdata->measurement[$curmodule->module_sensor_id]->sensor = $curmodule->module_sensor_id;
				$outdata->measurement[$curmodule->module_sensor_id]->value = $curmodule->value;
			}
			array_push($out, $outdata);
		}
		dd($out);


        //Build the dataset
        foreach ($sensorValues as $label => $values) {
            $dataset = array('\'label\'' => $label);

            if (isset($values['\'data\''])) {
                //Combine values of the same display window and get the average of those values
                $dataAverage = array_reduce($values['\'data\''], function ($a, $b) {
                    if (!isset($a[$b['window']])) {
                        $a[$b['window']] = $b;
                        $a[$b['window']]['value_count'] = 1;
                        $a[$b['window']]['average_value'] = array($b['value']);
                    } else {
                        $a[$b['window']]['value'] += $b['value'];
                        $a[$b['window']]['value_count'] += 1;
                        $a[$b['window']]['average_value'] = round($a[$b['window']]['value'] / $a[$b['window']]['value_count'], 2);
                    }
                    return $a;
                });

                //Replace data by the average values
                $values['\'data\''] = array();
                foreach ($dataAverage as $value) {
                    if (is_array($value['average_value']))
                        array_push($values['\'data\''], $value['average_value'][0]);
                    else
                        array_push($values['\'data\''], $value['average_value']);
                }
                $dataset = array_merge($dataset, $values);
            }

            //Push dataset to datasets
            array_push($data['\'datasets\''], $dataset);
        }

        return $data;
    }
}
