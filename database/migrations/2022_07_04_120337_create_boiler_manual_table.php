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
        Schema::create('boiler_manual', function (Blueprint $table) {
            $table->increments('id');
            $table->float('set_value')->nullable()->default(NULL);
            $table->float('min_value')->nullable()->default(NULL);
            $table->float('max_value')->nullable()->default(NULL);

            $table->foreign('id_object')->references('id')->on('objects')
                  ->onUpdate('cascade')->onDelete('set null');
        });
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
