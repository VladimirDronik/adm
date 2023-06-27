<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cameras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('image', 255);
            $table->string('link', 255);
            $table->unsignedInteger('room');
            $table->string('type', 20);
            $table->unsignedTinyInteger('sort');
            $table->boolean('active');
            $table->timestamps();

            $table->foreign('room')
                ->references('id')
                ->on('rooms')
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
        Schema::dropIfExists('cameras');
    }
}
