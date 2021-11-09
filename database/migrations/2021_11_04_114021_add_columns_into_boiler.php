<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIntoBoiler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('boiler')) {
            Schema::table('boiler', function (Blueprint $table) {

                $table->renameColumn('model', 'protocol');
                $table->boolean('thermostat');
                $table->boolean('boiler');
                $table->boolean('automode');
                $table->tinyInteger('water_temp')->nullable();
                $table->tinyInteger('feed_water_temp')->nullable();
                $table->tinyInteger('feed_heat_temp')->nullable();
                $table->tinyInteger('back_heat_temp')->nullable();
                $table->tinyInteger('target_heat_temp')->nullable();
                $table->tinyInteger('target_water_temp')->nullable();


                $table->dropColumn('mode');
                $table->dropColumn('state');
                $table->dropColumn('cooliant_supply');
                $table->dropColumn('cooliant_return');


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
        //
    }
}
