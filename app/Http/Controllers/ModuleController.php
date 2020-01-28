<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    //prevent non authorized access
    public function __construct()
    {
        $this->middleware('auth');
    }

    //load modules view
    public function index() {
        return view('modules');
    }
}
