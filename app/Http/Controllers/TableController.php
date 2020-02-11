<?php

namespace App\Http\Controllers;

use App\Measurement;
use App\Sensor;
use App\Sensor_added_value;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $request)
    {
        $fruit_type = $request->get('fruit_type');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $table_data = collect($this->table($fruit_type, $start_date, $end_date)); //can't send array so send collection instead
        $sensors = Sensor::get();
        $sensor_added_values = Sensor_added_value::get();
        return view('table', compact('table_data', 'sensors', 'sensor_added_values'));
    }

    public function table($fruit_type, $start_date = '', $end_date = '')
    {
        //get measurements filtered by date
        $measurements = Measurement::whereDate('measure_date', '>=', $start_date)->where('measure_date', '<=', $end_date)->get()->sortByDesc('measure_date')->values(); //->values() resets the key values after sort

        foreach ($measurements as $key => $measurement) {
            if ($measurement->module->field->fruit_type_id != $fruit_type) { //Remove unwanted fruit_types
                unset($measurements[$key]);
            }
        }

        $values = array();
        foreach ($measurements as $key => $item) {
            $values[$item['measure_date']][$item->module_id][] = $item;
        }

        return $values;
    }
}
