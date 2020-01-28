<?php

namespace App\Http\Controllers;

use App\Measurement;
use App\Module;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        //get measurements
        $measurements = Measurement::get();

        return view('home', array(
            'measurements' => $measurements,
        ));
    }
}
