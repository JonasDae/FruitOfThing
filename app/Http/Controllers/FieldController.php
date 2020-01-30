<?php

namespace App\Http\Controllers;

use App\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    //prevent non authorized access
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //get fields
        $fields = Field::get();

        return view('fields.index', compact('fields'));
    }
}
