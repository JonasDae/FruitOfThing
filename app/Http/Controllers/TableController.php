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
        //use paginate function on collection: https://gist.github.com/simonhamp/549e8821946e2c40a617c85d2cf5af5e
        $table_data = collect($this->table($fruit_type, $start_date, $end_date))->paginate(10); //can't send array so send collection instead
        $sensors = Sensor::get();
        return view('table', compact('table_data', 'sensors'));
    }

    public function table($fruit_type, $start_date = '', $end_date = '')
    {
        //get measurements filtered by date and fruit_type
        $measurements = DB::table('measurements')
            ->join('modules', 'measurements.module_id', '=', 'modules.id')
            ->join('fields', 'modules.field_id', '=', 'fields.id')
            ->join('module_sensors', 'measurements.module_sensor_id', '=', 'module_sensors.id')
            ->join('sensors', 'module_sensors.sensor_id', '=', 'sensors.id')
            ->select('measurements.module_id', 'measurements.module_sensor_id', 'measurements.value', 'measurements.measure_date', 'sensors.name', 'sensors.measuring_unit', 'sensors.id', 'modules.name as module_name')
            ->whereDate('measurements.measure_date', '>=', $start_date)
            ->whereDate('measurements.measure_date', '<=', $end_date)
            ->where('fields.fruit_type_id', '=', $fruit_type)
            ->orderByDesc('measure_date')
            ->get();

        $values = array();
        foreach ($measurements as $measurement) {
            $values[$measurement->measure_date][$measurement->module_name][] = $measurement;
        }

        return $values;
    }
}
