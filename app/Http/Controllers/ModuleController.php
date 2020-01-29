<?php

namespace App\Http\Controllers;

use App\Field;
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

        /* change uptime value */
        foreach ($modules as $module) {
            $date = date_create_from_format('Y-m-d H:i:s', $module->uptime);
            $current = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));

            $interval = date_diff($date, $current); //get difference between uptime and current datetime

            //convert difference to clean string
            $uptime = $interval->y !== 0 ? $interval->y . 'j ' : '';
            $uptime .= $interval->m !== 0 ? $interval->m . 'm ' : '';
            $uptime .= $interval->d !== 0 ? $interval->d . 'd ' : '';
            $uptime .= $interval->h !== 0 ? $interval->h . 'u ' : '';
            $uptime .= $interval->i !== 0 ? $interval->i . 'min ' : '';
            $uptime .= $interval->s !== 0 ? $interval->s . 'sec ' : '';

            $module->uptime = $uptime;
        }

        return view('modules.index', array(
            'modules' => $modules,
        ));
    }

    public function create() {
        $fields = Field::get();

        return view('modules.create', array(
            'fields' => $fields,
        ));
    }

    public function store() {
        $data = request()->validate(array(

        ));
    }
}
