<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermostatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('termostats')) {
            Schema::create('termostats', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('id_object')->nullable()->comment('id термостата из таблицы объектов');
                $table->double('current')->comment('текущая температура');
                $table->double('optimal')->comment('значение, которое должно быть в помещении');
                $table->double('gisteresis')->comment('гистерезис');
                $table->boolean('thermostat')->comment('0 - охлаждение, 1 - нагрев.');
                $table->unsignedInteger('object')->nullable()->comment('Объект, у которого будем менять состояние');
                $table->unsignedInteger('method_on')->nullable()->comment('Метод объекта при срабатывании термостата на включение');
                $table->unsignedInteger('method_off')->nullable()->comment('Метод объекта при срабатывании термостата на выключение');
                $table->string('id_termometr', 12)->nullable()->comment('id термометра для идентификации его по коду');
                $table->tinyInteger('min_threshold')->comment('минимальное значение, которое возможно в помещении');
                $table->tinyInteger('max_threshold')->comment('максимальное значение, которое возможно в помещении');
                $table->tinyInteger('min_alarm')->comment('минимальное значение аварии');
                $table->integer('max_alarm')->comment('максимальное значение аварии');
                $table->unsignedInteger('room')->nullable();

                $table->foreign('id_object')->references('id')->on('objects')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('object')->references('id')->on('objects')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('method_on')->references('id')->on('methods')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('method_off')->references('id')->on('methods')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('room')->references('id')->on('rooms')
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
        Schema::dropIfExists('termostats');
    }
}
