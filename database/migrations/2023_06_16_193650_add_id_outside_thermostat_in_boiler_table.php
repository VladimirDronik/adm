<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdOutsideThermostatInBoilerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boiler', function (Blueprint $table) {
            $table->integer('id_outside_thermostat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boiler', function (Blueprint $table) {
            $table->dropColumn('id_outside_thermostat');
        });
    }
}
