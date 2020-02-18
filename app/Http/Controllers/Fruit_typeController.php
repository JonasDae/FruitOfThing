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

        return view('fruits.index', compact('fruit_types'));
    }

    public function store(Request $request) {
        $data = $request->validate(array(
            'name' => array('required', 'string', 'max:255'),
        ));

        $fruit_type = new Fruit_type();
        $fruit_type->name = $data['name'];
        $fruit_type->timestamps = false; //don't update the updated_at column on save()
        $fruit_type->save();

        return back()->with('message', array('status' => 'success', 'content' => 'Fruittype aangemaakt!'));
    }

    public function update(Request $request) {
        $fruit_type = Fruit_type::find($request->get('id'));

        $data = $request->validate(array(
            'name' => array('required', 'string', 'max:255'),
        ));

        $fruit_type->name = $data['name'];
        $fruit_type->timestamps = false; //don't update the updated_at column on save()
        $fruit_type->save();

        return back()->with('message', array('status' => 'success', 'content' => 'Fruittype bijgewerkt!'));
    }

    public function destroy(Fruit_type $fruit_type) {
        $fruit_type->delete();
        return back()->with('message', array('status' => 'success', 'content' => 'Fruittype verwijderd!'));
    }
}
