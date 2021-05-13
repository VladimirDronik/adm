<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurtainsTable extends Migration
{
    public function up()
    {
        Schema::create('curtains', function (Blueprint $table) {

            $table->increments('id');
            $table->string('type', 20)->comment('Тип: штора, жалюзи, рольставня');
            $table->string('name', 100);
            $table->unsignedInteger('id_object')->nullable();
            $table->integer('port_open')->nullable()->comment('порт на открытие шторы');
            $table->integer('port_close')->nullable()->comment('порт на закрытие шторы');
            $table->tinyInteger('time')->comment('время полного открытия или закрытия шторы');
            $table->string('palce', 10)->comment('Размещение на обычном контроллере или на хитпро');

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
