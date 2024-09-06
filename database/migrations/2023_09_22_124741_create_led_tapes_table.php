<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedTapesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tapes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 155);
            $table->unsignedInteger('id_object');
            $table->string('type', 5);
            $table->string('status', 3);
            $table->smallInteger('h')->nullable();
            $table->smallInteger('s')->nullable();
            $table->smallInteger('v')->nullable();
            $table->smallInteger('w')->nullable();

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
        Schema::dropIfExists('tapes');
    }
}
