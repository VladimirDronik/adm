<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManometrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manometr', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->unsignedInteger('id_object')->nullable();
            $table->unsignedInteger('cur_value');
            $table->unsignedInteger('low_value');
            $table->unsignedInteger('low_object')->nullable();
            $table->unsignedInteger('low_method')->nullable();
            $table->unsignedInteger('high_value');
            $table->unsignedInteger('high_object')->nullable();
            $table->unsignedInteger('high_method')->nullable();
            $table->float('calibration');
            $table->unsignedInteger('room')->nullable();

            $table->foreign('id_object')->references('id')->on('objects')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('low_object')->references('id')->on('objects')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('low_method')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('high_object')->references('id')->on('objects')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('high_method')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('room')->references('id')->on('rooms')
                ->onUpdate('cascade')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manometr');
    }
}
