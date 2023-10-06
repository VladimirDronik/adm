<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoilerWaterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('boiler_water')) {
            Schema::create('boiler_water', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_object')->unsigned();
                $table->float('min_value')->nullable()->default(null);
                $table->float('max_value')->nullable()->default(null);

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
        Schema::dropIfExists('boiler_water');
    }
}
