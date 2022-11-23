<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoilerManualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('boiler_manual')) {
            Schema::create('boiler_manual', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_object')->unsigned();
                $table->float('set_value')->nullable()->default(NULL);
                $table->float('min_value')->nullable()->default(NULL);
                $table->float('max_value')->nullable()->default(NULL);

                $table->foreign('id_object')->references('id')->on('objects')
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
        Schema::dropIfExists('boiler_manual');
    }
}
