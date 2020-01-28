<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorAddedValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_added_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('module_sensor_id');
            $table->float('value');
            $table->dateTime('start_date');

            $table->index('module_sensor_id');
            $table->foreign('module_sensor_id')->references('id')->on('module_sensors')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensor_added_values');
    }
}
