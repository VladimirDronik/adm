<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateLocksTable extends Migration
{
    public function up()
    {
        Schema::create('locks', function (Blueprint $table) {

            $table->increments('id');
            $table->string('type', 20)->comment('Тип: электромеханический, магнитный, защелка');
            $table->string('name', 100);
            $table->unsignedInteger('id_object')->nullable();
            $table->integer('port_open')->nullable()->comment('порт на открытие замка');
            $table->integer('port_close')->nullable()->comment('порт на закрытие замка');
            $table->tinyInteger('time')->comment('время полного открытия или закрытия замка');
            $table->string('place', 10)->comment('Размещение на обычном контроллере или на хитпро');

            $table->foreign('id_object')->references('id')->on('objects')
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
        Schema::dropIfExists('curtains');
    }
}
