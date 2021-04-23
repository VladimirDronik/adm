<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionsTable extends Migration
{
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {


            $table->increments('id');
            $table->unsignedInteger('id_event')->nullable()->comment('событие, для которых назначены действия');
            $table->string('type', 20)->comment('тип действия');
            $table->string('value', 255)->nullable()->comment('значение для действия');
            $table->string('value', 255)->nullable()->comment('значение для действия');
            $table->string('params', 255)->nullable()->comment('значение для действия');
            $table->tinyInteger('active');

            $table->foreign('id_event')->references('id')->on('events')
                ->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boiler');
    }
}
