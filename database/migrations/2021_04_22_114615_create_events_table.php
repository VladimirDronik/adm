<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {


            $table->increments('id');
            $table->unsignedInteger('id_object')->nullable();
            $table->string('name',150);
            $table->string('event',50);
            $table->string('property',50);
            $table->string('comparison',2);
            $table->string('value',5);
            $table->unsignedInteger('script')->nullable()->comment('id скрипта из таблицы скриптов');
            $table->unsignedInteger('method')->nullable()->comment('id метода из таблицы методов');
            $table->unsignedInteger('notification')->nullable()->comment('id уведомления');

            $table->foreign('id_object')->references('id')->on('objects')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('script')->references('id')->on('scripts')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('method')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('notification')->references('id')->on('notifsettings')
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
        Schema::dropIfExists('boiler');
    }
}
