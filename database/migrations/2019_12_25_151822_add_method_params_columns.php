<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMethodParamsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('methods', function (Blueprint $table) {
            if (!Schema::hasColumn('methods', 'params')) {
                $table->string('params', 100)->nullable()
                    ->comment('Если null, то метод без параметров, иначе названия параметров через символ ;');
            }
        });

        Schema::table('ports', function (Blueprint $table) {
            if (!Schema::hasColumn('ports', 'method_params')) {
                $table->string('method_params', 100)->nullable();
            }
            if (!Schema::hasColumn('ports', 'dc_method_params')) {
                $table->string('dc_method_params', 100)->nullable();
            }
            if (!Schema::hasColumn('ports', 'lc_method_params')) {
                $table->string('lc_method_params', 100)->nullable();
            }
        });

        Schema::table('termostats', function (Blueprint $table) {
            if (!Schema::hasColumn('termostats', 'method_on_params')) {
                $table->string('method_on_params', 100)->nullable();
            }
            if (!Schema::hasColumn('termostats', 'method_off_params')) {
                $table->string('method_off_params', 100)->nullable();
            }
        });

        Schema::table('view_items', function (Blueprint $table) {
            if (!Schema::hasColumn('view_items', 'on_method_params')) {
                $table->string('on_method_params', 100)->nullable();
            }
        });

        Schema::table('scheduler_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('scheduler_tasks', 'method_params')) {
                $table->string('method_params', 100)->nullable();
            }
        });
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
