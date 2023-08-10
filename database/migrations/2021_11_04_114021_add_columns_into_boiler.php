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
            });
        }

        if (Schema::hasTable('boiler')) {
            Schema::table('boiler', function (Blueprint $table) {
                $table->boolean('thermostat')->default(0);
            });
        }

        if (Schema::hasTable('boiler')) {
            Schema::table('boiler', function (Blueprint $table) {
                $table->boolean('boiler')->default(0);
            });
        }

        if (Schema::hasTable('boiler')) {
            Schema::table('boiler', function (Blueprint $table) {
                $table->boolean('lock')->default(0);
            });
        }

        if (Schema::hasTable('boiler')) {
            Schema::table('boiler', function (Blueprint $table) {
                $table->tinyInteger('water_temp')->nullable();
            });
        }

        if (Schema::hasTable('boiler')) {
            Schema::table('boiler', function (Blueprint $table) {
                $table->tinyInteger('feed_water_temp')->nullable();
            });
        }

        if (Schema::hasTable('boiler')) {
            Schema::table('boiler', function (Blueprint $table) {
                $table->tinyInteger('feed_heat_temp')->nullable();
            });
        }

        if (Schema::hasTable('boiler')) {
            Schema::table('boiler', function (Blueprint $table) {
                $table->tinyInteger('back_heat_temp')->nullable();
            });
        }

        if (Schema::hasTable('boiler')) {
            Schema::table('boiler', function (Blueprint $table) {
                $table->tinyInteger('target_heat_temp')->nullable();
            });
        }

        if (Schema::hasTable('boiler')) {
            Schema::table('boiler', function (Blueprint $table) {
                $table->tinyInteger('target_water_temp')->nullable();
            });
        }

        if (Schema::hasTable('boiler')) {
            Schema::table('boiler', function (Blueprint $table) {
                $table->dropColumn(['state', 'cooliant_supply', 'cooliant_return']);
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
