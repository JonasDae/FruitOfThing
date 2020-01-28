<?php

namespace App\Http\Controllers;

use App\Fruit_type;
use Illuminate\Http\Request;

class Fruit_typeController extends Controller
{
    //prevent non authorized access
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //get fruit types
        $fruit_types = Fruit_type::get();

        return view('fruits', array(
            'fruit_types' => $fruit_types,
        ));
    }
}
