<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemperaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('temperatures')) {
            Schema::create('temperatures', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_room');
                $table->float('normal');
                $table->float('night');
                $table->float('eco');
                $table->tinyInteger('sort');

                $table->foreign('id_room')->references('id')->on('rooms')
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temperatures');
    }
}
