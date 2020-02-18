<?php

namespace App\Http\Controllers;

use App\Field;
use App\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    //prevent non authorized access
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        //get sensors & modules (filter on field_id if field_id is given, else get all modules)
        $field_id = $request->get('field_id');
        $modules = Module::get()->when($field_id, function ($query, $field_id) {
            return $query->where('field_id', $field_id);
        })->sortBy('name');
        $fields = Field::get();

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

        return view('modules.index', compact('modules', 'fields'));
    }

    public function update(Request $request) {
        $module = Module::find($request->get('id'));

        $data = $request->validate(array(
            'name' => array('required', 'string', 'max:255', 'unique:modules'),
            'field' => array('required', 'exists:fields,id'),
            'phone_number' => array('required', 'min:10', 'max:11'),
        ));

        $module->name = $data['name'];
        $module->field_id = (int)$data['field'];
        $module->phone_number = $data['phone_number'];
        $module->timestamps = false; //don't update the updated_at column on save()
        $module->save();

        return back()->with('message', array('status' => 'success', 'content' => 'Module bijgewerkt!'));
    }

    public function destroy(Module $module) {
        $module->delete();
        return back()->with('message', array('status' => 'success', 'content' => 'Module verwijderd!'));
    }
}
