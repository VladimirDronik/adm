<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIntoSwitches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('switches')) {
            Schema::table('switches', function (Blueprint $table) {
                $table->unsignedInteger('id_method')->nullable();
            });
        }

        if (Schema::hasTable('switches')) {
            Schema::table('switches', function (Blueprint $table) {
                $table->string('method_params', 255)->nullable();
            });
        }

        if (Schema::hasTable('switches')) {
            Schema::table('switches', function (Blueprint $table) {
                $table->foreign('id_method')->references('id')->on('methods')
                    ->onUpdate('cascade')->onDelete('set null');
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
