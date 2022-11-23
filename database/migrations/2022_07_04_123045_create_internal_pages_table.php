<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('internalPages')) {
            Schema::create('internalPages', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('idElement')->nullable()->default(NULL);

                $table->foreign('idElement')->references('id')->on('elements')
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
        Schema::dropIfExists('internalPages');
    }
}
