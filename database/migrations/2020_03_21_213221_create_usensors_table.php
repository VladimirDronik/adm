<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('usensors')) {
            Schema::create('usensors', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('id_object')->nullable()->comment('id датчика из таблицы объектов');
                $table->string('name')->comment('название датчика');
                $table->double('temp')->nullable()->comment('текущая температура');
                $table->double('hum')->nullable()->comment('текущая влажность');
                $table->double('lux')->nullable()->comment('текущий уровень освещенности');
                $table->unsignedInteger('device_id')->comment('id устройства (контроллера), на котором висит датчик');
                $table->unsignedInteger('port_SCL')->comment('порт SCL контроллера на котором висит датчик');
                $table->unsignedInteger('port_SDA')->comment('порт SDA контроллера на котором висит датчик');
                $table->unsignedInteger('room')->nullable();

                $table->foreign('id_object')->references('id')->on('objects')
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
        Schema::dropIfExists('usensors');
    }
}
