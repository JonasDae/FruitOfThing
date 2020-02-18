<?php

namespace App\Http\Controllers;

use App\Fruit_type;
use App\Measurement;
use App\Sensor_added_value;


class HomeController extends Controller
{
    public function index()
    {
        //get fruit_types
        $fruit_types = Fruit_type::get();

        return view('home', compact('fruit_types'));
    }
}
