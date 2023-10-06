<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableHygrostats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('hygrostats')) {
            Schema::create('hygrostats', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->unsignedInteger('id_object')->nullable()->comment('id гигростата из таблицы объектов');
                $table->double('current')->nullable()->comment('текущая влажность');
                $table->double('optimal')->comment('значение, которое должно быть в помещении');
                $table->double('gisteresis')->comment('гистерезис');
                $table->boolean('type')->comment('0 - осушение, 1 - увлажнение');
                $table->unsignedInteger('object')->nullable()->comment('Объект, у которого будем менять состояние');
                $table->unsignedInteger('method_on')->nullable()->comment('Метод объекта при срабатывании гигростата на включение');
                $table->string('method_on_params', 100)->nullable();
                $table->unsignedInteger('method_off')->nullable()->comment('Метод объекта при срабатывании гагростата на выключение');
                $table->string('method_off_params', 100)->nullable();
                $table->tinyInteger('min_threshold')->comment('минимальное значение, которое возможно в помещении');
                $table->tinyInteger('max_threshold')->comment('максимальное значение, которое возможно в помещении');
                $table->tinyInteger('min_alarm')->comment('минимальное значение аварии');
                $table->integer('max_alarm')->comment('максимальное значение аварии');
                $table->unsignedInteger('room')->nullable();
                $table->integer('subdev_id')->nullable();
                $table->unsignedInteger('usensor_id')->nullable();
                $table->string('placetype', 10)->nullable();

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
                $table->foreign('usensor_id')->references('id_object')->on('usensors')
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
        Schema::dropIfExists('hygrostats');
    }
}
