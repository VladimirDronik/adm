<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSetValueInBoilerManualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boiler_manual', function (Blueprint $table) {
            $table->integer('set_value')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boiler_manual', function (Blueprint $table) {
            $table->float('set_value')->nullable()->default(null)->change();
        });
    }
}
