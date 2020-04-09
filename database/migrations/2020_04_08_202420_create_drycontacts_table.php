<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDryContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drycontacts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_object')->nullable()->comment('id датчика из таблицы объектов');
            $table->string('name');

            $table->foreign('id_object')->references('id')->on('objects')
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
        Schema::dropIfExists('drycontacts');
    }
}
