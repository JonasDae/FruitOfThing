<?php

namespace App\Http\Controllers;

use App\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    //prevent non authorized access
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //get modules
        $sensors = Sensor::get();

        return view('sensor', array(
            'sensors' => $sensors,
        ));
    }
}
