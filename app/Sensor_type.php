<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sensor_type extends Model
{
    public function sensor() {
        return $this->hasMany(Sensor::class);
    }
}
