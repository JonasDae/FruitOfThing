<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    public function fruit_type() {
        return $this->belongsTo(Fruit_type::class);
    }

    public function module() {
        return $this->hasMany(Module::class);
    }
}
