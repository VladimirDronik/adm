<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoilerGvsTable extends Migration
{
    public function up()
    {
        Schema::create('boiler_gvs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100);
            $table->string('model',30);
            $table->unsignedInteger('id_object')->nullable();
            $table->tinyInteger('state')->nullable();
            $table->string('mode', 6)->nullable();
            $table->tinyInteger('pressue')->nullable();
            $table->string('ip_address',15);

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
        Schema::dropIfExists('boiler_gvs');
    }
}
