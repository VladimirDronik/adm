<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoilerAutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boiler_auto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('t_out');
            $table->integer('t_water');
            $table->unsignedInteger('id_object');

            $table->foreign('id_object')
                ->references('id')
                ->on('objects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boiler_auto');
    }
}
