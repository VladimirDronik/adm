<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoilerWaterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boiler_water', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_object');
            $table->float('set_value')->nullable()->default(NULL);
            $table->float('min_value')->nullable()->default(NULL);
            $table->float('max_value')->nullable()->default(NULL);
        });
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
