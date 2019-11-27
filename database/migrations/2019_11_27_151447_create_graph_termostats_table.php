<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraphTermostatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('graph_termostats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_termostat')->comment('id термостата из таблицы термостатов');
            $table->dateTime('datetime')->comment('дата и время значения');
            $table->double('value')->comment('значение параметра');

            $table->foreign('id_termostat')->references('id')->on('termostats')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('graph_termostats');
    }
}
