<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    public function module() {
        return $this->hasOne(Module::class);
    }

    public function module_sensor() {
        return $this->belongsTo('App\Module_sensor');
    }
    public function sensor() {
        return $this->hasOne(Sensor::class);
    }
}
