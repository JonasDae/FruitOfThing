<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function field() {
        return $this->belongsTo(Field::class);
    }

    public function sensor() {
        return $this->hasMany(Sensor::class);
    }

    public function measurement() {
        return $this->hasMany(Measurement::class);
    }
}
