<?php

namespace App\Http\Controllers;

use App\Fruit_type;
use App\Measurement;
use App\Notification;
use App\Module;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        //get measurements & fruit_types
        $notifications = Notification::get()->sortByDesc('send_date');
        $measurements = Measurement::get();
        $fruit_types = Fruit_type::get();

        return view('home', array(
            'notifications' => $notifications,
            'measurements' => $measurements,
            'fruit_types' => $fruit_types,
        ));
    }
}
