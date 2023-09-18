<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtensionModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extension_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('extension_module_type_id');
            $table->unsignedInteger('device_id');
            $table->integer('sda_port');
            $table->integer('scl_port');
            $table->timestamps();

            $table->foreign('extension_module_type_id')
                ->references('id')
                ->on('extension_module_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extension_modules');
    }
}
