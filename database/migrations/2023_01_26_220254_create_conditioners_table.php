<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditioners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_object');
            $table->unsignedInteger('device_id');
            $table->string('wb_mir');
            $table->unsignedInteger('model');
            $table->unsignedInteger('id_room');
            $table->timestamps();

            $table->foreign('id_object')
                ->references('id')
                ->on('objects')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('model')
                ->references('id')
                ->on('conditioner_models')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('id_room')
                ->references('id')
                ->on('rooms')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
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
        Schema::dropIfExists('conditioners');
    }
}
