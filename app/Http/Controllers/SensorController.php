<?php

namespace App\Http\Controllers;

use App\Module_sensor;

class SensorController extends Controller
{
    //prevent non authorized access
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        //get sensors
        $sensors = Module_sensor::get()->sortBy('module_id');

        return view('sensors.index', array(
            'sensors' => $sensors,
        ));
    }
}
