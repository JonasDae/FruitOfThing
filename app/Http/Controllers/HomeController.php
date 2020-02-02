<?php

namespace App\Http\Controllers;

use App\Fruit_type;
use App\Measurement;
use App\Notification;
use App\Sensor_added_value;


class HomeController extends Controller
{
    public function index()
    {
        //get measurements & fruit_types & notifications
        $notifications = Notification::get()->sortByDesc('send_date');
        $measurements = Measurement::get()->sortByDesc('measure_date');
        $fruit_types = Fruit_type::get();
        $sensor_added_values = Sensor_added_value::get();

        return view('home', array(
            'notifications' => $notifications,
            'measurements' => $measurements,
            'fruit_types' => $fruit_types,
            'sensor_added_values' => $sensor_added_values,
        ));
    }
}
