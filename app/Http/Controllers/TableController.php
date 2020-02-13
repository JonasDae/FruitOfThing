<?php

namespace App\Http\Controllers;

use App\Sensor;
use App\Sensor_added_value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        //get measurements filtered by date and fruit_type
        $measurements = DB::table('measurements')
            ->join('modules', 'measurements.module_id', '=', 'modules.id')
            ->join('fields', 'modules.field_id', '=', 'fields.id')
            ->join('module_sensors', 'measurements.module_sensor_id', '=', 'module_sensors.id')
            ->join('sensors', 'module_sensors.sensor_id', '=', 'sensors.id')
            ->select('measurements.id', 'measurements.module_id', 'measurements.module_sensor_id', 'measurements.value', 'measurements.measure_date', 'fields.fruit_type_id', 'sensors.name', 'sensors.measuring_unit')
            ->whereDate('measurements.measure_date', '>=', $start_date)
            ->whereDate('measurements.measure_date', '<=', $end_date)
            ->where('fields.fruit_type_id', '=', $fruit_type)
            ->get();


        //dd($measurements[0]->id);
        $values = array();
        foreach ($measurements as $key => $item) {
            $values[$item->measure_date][$item->module_id][] = $item;
        }

        return $values;
    }
}
