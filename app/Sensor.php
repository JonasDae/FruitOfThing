<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    public function module_sensor() {
        return $this->belongsTo(Module_sensor::class);
    }
}
