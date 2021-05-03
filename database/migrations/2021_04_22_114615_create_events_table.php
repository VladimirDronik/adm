<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {


            $table->increments('id');
            $table->unsignedInteger('id_object')->nullable();
            $table->string('name',150);
            $table->string('event',50)->nullable();
            $table->string('property',50)->nullable();
            $table->string('comparison',2)->nullable();
            $table->string('value',5)->nullable();


            $table->foreign('id_object')->references('id')->on('objects')
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
        Schema::dropIfExists('events');
    }
}
