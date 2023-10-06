<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyingColumnInBoiler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boiler', function (Blueprint $table) {
            $table->float('pressure', 10, 1, true)->change();
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
            $table->smallInteger('pressure')->change();
        });
    }
}
