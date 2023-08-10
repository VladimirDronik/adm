<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIntoTermostats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('termostats')) {
            Schema::table('termostats', function (Blueprint $table) {
                $table->integer('subdev_id')->nullable();
            });
        }

        if (Schema::hasTable('termostats')) {
            Schema::table('termostats', function (Blueprint $table) {
                $table->float('current')->nullable()->change();
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
