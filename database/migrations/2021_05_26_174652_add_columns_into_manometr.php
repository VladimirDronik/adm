<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIntoManometr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('manometr')) {
            Schema::table('manometr', function (Blueprint $table) {
                $table->string('low_method_params', 100)->nullable();
            });
        }

        if (Schema::hasTable('manometr')) {
            Schema::table('manometr', function (Blueprint $table) {
                $table->string('high_method_params', 100)->nullable();
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
