<?php

namespace App\Http\Controllers;

use App\Fruit_type;
use App\Measurement;
use App\Module;
use Illuminate\Http\Request;
use stdClass;

class HomeController extends Controller
{
	private function chart_dataset($label, $axis, $type, $color, $order, $data)
	{
		
		$out = new stdClass();
		$out->yAxisID = $axis;
		$out->label = $label;
		$out->data = $data;
		$out->type = $type;
		$out->borderColor = $color;
		$out->fill = $color;
		$out->hoverBorderWidth = 3;
		$out->hoverBorderColor = $color;
		$out->order = $order;
		return $out;
	}
	private function chart_build()
	{
        $measurements = Measurement::get();

		$out = new stdClass();
		$out->type = 'bar';
		$out->data = new stdClass();
		$out->data->labels = ["ASD", "BCD", "DJKLD"]; 
		$out->data->datasets = [];
		$data = new stdClass();
		$out->data->datasets[0] = $this->chart_dataset("data alias", "axis1", "bartype", "#FF00FF", 3, $data);
		return $out;
	}
    public function index()
    {
        //get measurements & fruit_types
        $measurements = Measurement::get();
        $fruit_types = Fruit_type::get();

        return view('home', array(
            'measurements' => $measurements,
            'fruit_types' => $fruit_types,
            'chart_data' => $this->chart_build(),
        ));
    }
}
