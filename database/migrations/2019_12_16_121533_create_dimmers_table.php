<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('dimmers')) {
            Schema::create('dimmers', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100);
                $table->unsignedInteger('id_object')->nullable();
                $table->tinyInteger('value');
                $table->tinyInteger('speed');

                $table->foreign('id_object')->references('id')->on('objects')
                    ->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('dimmers');
    }
}
