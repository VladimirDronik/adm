<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraphLightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('graph_lights', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_count')->comment('id датчика освещенности');
            $table->dateTime('datetime')->comment('дата и время значения');
            $table->double('value')->comment('значение параметра');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('graph_lights');
    }
}
