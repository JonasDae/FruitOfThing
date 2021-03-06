<?php

namespace App\Http\Controllers;

use App\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    public function index(Request $request)
    {
        $fruit_type = $request->get('fruit_type');
        $display = $request->get('display');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $graph_data = collect($this->graph($fruit_type, $display, $start_date, $end_date)); //can't send array so send collection instead
        return view('graph', compact('graph_data'));
    }

    public function graph($fruit_type, $display = 'm', $start_date = '', $end_date = '')
    {
        //Get measurements and filter by date and fruit_type
        $measurements = DB::table('measurements')
            ->join('modules', 'measurements.module_id', '=', 'modules.id')
            ->join('fields', 'modules.field_id', '=', 'fields.id')
            ->join('module_sensors', 'measurements.module_sensor_id', '=', 'module_sensors.id')
            ->join('sensors', 'module_sensors.sensor_id', '=', 'sensors.id')
            ->select('measurements.id', 'measurements.module_id', 'measurements.module_sensor_id', 'measurements.value', 'measurements.measure_date', 'fields.fruit_type_id', 'sensors.name_alias')
            ->whereDate('measurements.measure_date', '>=', $start_date)
            ->whereDate('measurements.measure_date', '<=', $end_date)
            ->where('fields.fruit_type_id', '=', $fruit_type)
            ->orderBy('measurements.measure_date')
            ->get();
        $sensors = Sensor::get();

        $data = array( //embed key's in single quotes in order to be able to replace them into the javascript code at graph.blade.php
            '\'labels\'' => array(), //1 label for each display window
            '\'datasets\'' => array(), //1 dataset for each sensor type
        );

        //Fill color
        $sensorValues = array();
        foreach ($sensors as $sensor) {
            $sensorValues[$sensor->name_alias]['\'backgroundColor\''] = $sensor->graph_type == 'bar' ? $sensor->color : 'transparent';
            $sensorValues[$sensor->name_alias]['\'borderColor\''] = $sensor->color;
            $sensorValues[$sensor->name_alias]['\'hoverBorderColor\''] = 'black';
            $sensorValues[$sensor->name_alias]['\'hoverBorderWidth\''] = 3;
            $sensorValues[$sensor->name_alias]['\'type\''] = $sensor->graph_type;
        }

        //Fill data
        foreach ($measurements as $measurement) {
            if (empty($sensorValues[$measurement->name_alias]['\'data\''])) {
                $sensorValues[$measurement->name_alias]['\'data\''] = array();
            }
            array_push($sensorValues[$measurement->name_alias]['\'data\''], array('window' => $this->get_display_window($measurement, $display), 'value' => $measurement->value));

            //Update labels
            if (!in_array($this->get_display_window($measurement, $display), $data['\'labels\'']))
                array_push($data['\'labels\''], $this->get_display_window($measurement, $display));
        }

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

    public function get_display_window($measurement, $display)
    {
        $displays = array('Y', 'm', 'W', 'd', 'H'); //different views
        $view = '';
        $i = 0;
        while (($displays[$i - 1] ?? '') !== $display) {
            if (($displays[$i] ?? '') == 'H')
                $view .= ' ' . date(str_replace('m', 'M', $displays[$i]), strtotime($measurement->measure_date)) . 'u'; //Label add hour
            else if (($displays[$i] ?? '') == 'W' && $display == 'W')
                $view .= '(week ' . date(str_replace('m', 'M', $displays[$i]), strtotime($measurement->measure_date)) . ')'; //Label add week
            else if (($displays[$i] ?? '') != 'W')
                $view = date(str_replace('m', 'M', $displays[$i]), strtotime($measurement->measure_date)) . " $view"; //Label add other displays
            $i++;
        }
        return trim($view);
    }
}
