<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIntoMotionsensor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('motionsensors')) {
            Schema::table('motionsensors', function (Blueprint $table) {
                $table->string('method_normal_params', 100)->nullable();
            });
        }

        if (Schema::hasTable('motionsensors')) {
            Schema::table('motionsensors', function (Blueprint $table) {
                $table->string('method_eco_params', 100)->nullable();
            });
        }

        if (Schema::hasTable('motionsensors')) {
            Schema::table('motionsensors', function (Blueprint $table) {
                $table->string('method_night_params', 100)->nullable();
            });
        }

        if (Schema::hasTable('motionsensors')) {
            Schema::table('motionsensors', function (Blueprint $table) {
                $table->string('method_morning_params', 100)->nullable();
            });
        }

        if (Schema::hasTable('motionsensors')) {
            Schema::table('motionsensors', function (Blueprint $table) {
                $table->string('method_evening_params', 100)->nullable();
            });
        }

        if (Schema::hasTable('motionsensors')) {
            Schema::table('motionsensors', function (Blueprint $table) {
                $table->string('method_guard_params', 100)->nullable();
            });
        }

        if (Schema::hasTable('motionsensors')) {
            Schema::table('motionsensors', function (Blueprint $table) {
                $table->string('method_light_params', 100)->nullable();
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