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

    public function getByFruitType($fruit_type_id) {
        return DB::table('measurements')
            ->join('modules', function ($join) {
                $join->on('measurements.module_id', '=', 'modules.id');
            })
            ->join('fields', function ($join) {
                $join->on('modules.field_id', '=', 'fields.id');
            })
            ->join('fruit_types', function ($join) {
                $join->on('fields.fruit_type_id', '=', 'fruit_types.id');
            })->where('fruit_types.id', '=', $fruit_type_id)->get();
    }
}
