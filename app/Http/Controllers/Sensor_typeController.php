<?php

namespace App\Http\Controllers;

use App\Sensor;
use Illuminate\Http\Request;

class Sensor_typeController extends Controller
{
    //prevent non authorized access
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //get sensor types
        $sensors = Sensor::get();

        return view('sensor_types.index', compact('sensors'));
    }

    public function store(Request $request) {
        $data = $request->validate(array(
            'name' => array('required', 'string', 'max:255'),
            'name_alias' => array('required', 'string', 'max:255'),
            'measuring_unit' => array(),
            'color' => array('required'),
            'graph_type' => array('required'),
        ));

        $sensor = new Sensor();
        $sensor->name = $data['name'];
        $sensor->name_alias = $data['name_alias'];
        $sensor->measuring_unit = $data['measuring_unit'];
        $sensor->color = $data['color'];
        $sensor->graph_type = $data['graph_type'];
        $sensor->timestamps = false;
        $sensor->save();

        return back()->with('message', array('status' => 'success', 'content' => 'Sensor type aangemaakt!'));
    }

    public function update(Request $request) {
        $sensor = Sensor::find($request->get('id'));

        $data = $request->validate(array(
            'name' => array('required', 'string', 'max:255'),
            'name_alias' => array('required'),
            'measuring_unit' => array(),
            'color' => array('required'),
            'graph_type' => array('required'),
        ));

        $sensor->name = $data['name'];
        $sensor->name_alias = $data['name_alias'];
        $sensor->measuring_unit = $data['measuring_unit'];
        $sensor->color = $data['color'];
        $sensor->graph_type = $data['graph_type'];
        $sensor->timestamps = false;
        $sensor->update();

        return back()->with('message', array('status' => 'success', 'content' => 'Sensor type bijgewerkt!'));
    }

    public function destroy(Sensor $sensor) {
        $sensor->delete();
        return back()->with('message', array('status' => 'success', 'content' => 'Sensor type verwijderd!'));
    }
}
