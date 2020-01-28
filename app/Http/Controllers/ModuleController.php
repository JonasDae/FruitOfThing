<?php

namespace App\Http\Controllers;

use App\Module;
use App\User;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    //prevent non authorized access
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        //get modules
        $modules = Module::get();

        return view('modules', array(
            'modules' => $modules,
        ));
    }
}
