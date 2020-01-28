<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fruit_type extends Model
{
    public function field() {
        return $this->hasMany(Field::class);
    }
}
