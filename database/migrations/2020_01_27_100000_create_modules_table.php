<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('field_id')->unsigned()->nullable();
            $table->unsignedBigInteger('battery_level')->nullable();
            $table->string('phone_number')->nullable();
            $table->dateTime('uptime');
            $table->dateTime('last_connection');

            $table->index('field_id');
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
