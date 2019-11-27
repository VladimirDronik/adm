<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propertyes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->unsignedInteger('id_object');
            $table->string('value', 50);

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
        Schema::dropIfExists('propertyes');
    }
}
