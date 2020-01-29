<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function module_sensor() {
        return $this->belongsTo(Module_sensor::class);
    }
}
