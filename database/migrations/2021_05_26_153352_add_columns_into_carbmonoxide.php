<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIntoCarbmonoxide extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('carbmonoxide')) {
            Schema::table('carbmonoxide', function (Blueprint $table) {
                $table->string('low_method_params', 100)->nullable();
            });
        }

        if (Schema::hasTable('carbmonoxide')) {
            Schema::table('carbmonoxide', function (Blueprint $table) {
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
