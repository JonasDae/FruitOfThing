<?php

namespace App\Http\Controllers;

use App\Field;
use App\Fruit_type;
use App\Sensor;
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
        //get fields & fruit_types
        $fields = Field::get();
        $fruit_types = Fruit_type::get();

        return view('fields.index', compact('fields', 'fruit_types'));
    }

    public function store(Request $request) {
        $data = $request->validate(array(
            'name' => array('required', 'string', 'max:255'),
            'fruit_type' => array('required', 'exists:fruit_types,id'),
            'adres' => array('required', 'string', 'max:255'),
            'postcode' => array('required', 'min:4', 'max:4'),
        ));

        $field = new Field();
        $field->name = $data['name'];
        $field->fruit_type_id = (int)$data['fruit_type'];
        $field->adres = $data['adres'];
        $field->postcode = $data['postcode'];
        $field->timestamps = false; //don't update the updated_at column on save()
        $field->save();

        return redirect(route('fields.index'));
    }

    public function update(Request $request) {
        $field = Field::find($request->get('id'));

        $data = $request->validate(array(
            'name' => array('required', 'string', 'max:255'),
            'fruit_type' => array('required', 'exists:fruit_types,id'),
            'adres' => array('required', 'string', 'max:255'),
            'postcode' => array('required', 'min:4', 'max:4'),
        ));

        $field->name = $data['name'];
        $field->fruit_type_id = (int)$data['fruit_type'];
        $field->adres = $data['adres'];
        $field->postcode = $data['postcode'];
        $field->timestamps = false; //don't update the updated_at column on save()
        $field->save();

        return redirect(route('fields.index'));
    }

    public function destroy(Field $field) {
        $field->delete();
        return redirect(route('fields.index'));
    }
}
