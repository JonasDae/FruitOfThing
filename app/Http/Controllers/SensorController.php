<?php

namespace App\Http\Controllers;

use App\Module_sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    //prevent non authorized access
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        //get sensors (filter on module_id if module_id is given, else get all sensors)
        $module_id = $request->get('module_id');
        $sensors = Module_sensor::get()->when($module_id, function ($query, $module_id) {
            return $query->where('module_id', $module_id);
        })->sortBy('module_id');

        return view('sensors.index', compact('sensors'));
    }
}
