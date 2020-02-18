<?php

namespace App\Http\Controllers;

use App\Fruit_type;

class HomeController extends Controller
{
    public function index()
    {
        //get fruit_types
        $fruit_types = Fruit_type::get();

        return view('home', compact('fruit_types'));
    }
}
