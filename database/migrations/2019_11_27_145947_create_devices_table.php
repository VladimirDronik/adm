<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('devices')) {
            Schema::create('devices', function (Blueprint $table) {
                $table->increments('id');
                $table->string('ip_address', 15);
                $table->string('description');
                $table->unsignedInteger('type');
                $table->boolean('active');

                $table->foreign('type')->references('id')->on('devtypes')
                    ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('devices');
    }
}
