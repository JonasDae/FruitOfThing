<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function sensor_type() {
        return $this->belongsTo(Sensor_type::class);
    }

    public function measurement() {
        return $this->hasMany(Measurement::class);
    }
}
