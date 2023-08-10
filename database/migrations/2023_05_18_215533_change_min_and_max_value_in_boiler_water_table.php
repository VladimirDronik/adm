<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMinAndMaxValueInBoilerWaterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boiler_water', function (Blueprint $table) {
            $table->integer('min_value')->nullable()->default(null)->change();
        });

        Schema::table('boiler_water', function (Blueprint $table) {
            $table->integer('max_value')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boiler_water', function (Blueprint $table) {
            $table->float('min_value')->nullable()->default(null)->change();
        });

        Schema::table('boiler_water', function (Blueprint $table) {
            $table->float('max_value')->nullable()->default(null)->change();
        });
    }
}
