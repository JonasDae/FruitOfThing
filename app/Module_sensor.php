<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module_sensor extends Model
{
	public function sensor() {
		return $this->belongsTo(Sensor::class);
	}

	public function module() {
	    return $this->belongsTo(Module::class);
    }

}
