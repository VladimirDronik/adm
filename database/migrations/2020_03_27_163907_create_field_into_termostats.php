<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldIntoTermostats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('termostats', function (Blueprint $table) {
            if (! Schema::hasColumn('termostats', 'usensor_id')) {
                $table->unsignedInteger('usensor_id')->nullable();
            }

            if (! Schema::hasColumn('termostats', 'placetype')) {
                $table->string('placetype', 10)->nullable();
            }

            $table->foreign('usensor_id')->references('id_object')->on('usensors')
                ->onUpdate('cascade')->onDelete('set null');
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
