<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLightstatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('lightstats')) {
            Schema::create('lightstats', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->unsignedInteger('id_object')->nullable()->comment('id светостата из таблицы объектов');
                $table->double('current')->comment('текущая освещенность');
                $table->double('optimal')->comment('значение, которое должно быть в помещении');
                $table->double('gisteresis')->comment('гистерезис');
                $table->boolean('mode')->comment('0 - реакция на потемнение, 1 - реакция на посветление');
                $table->unsignedInteger('object')->nullable()->comment('Объект, у которого будем менять состояние');
                $table->unsignedInteger('method_on')->nullable()->comment('Метод объекта при срабатывании светостата на включение');
                $table->unsignedInteger('method_off')->nullable()->comment('Метод объекта при срабатывании светостата на выключение');
                $table->string('method_on_params', 100)->nullable();
                $table->string('method_off_params', 100)->nullable();
                $table->Integer('min_threshold')->comment('минимальное значение, которое возможно в помещении');
                $table->Integer('max_threshold')->comment('максимальное значение, которое возможно в помещении');
                $table->Integer('min_alarm')->comment('минимальное значение аварии');
                $table->integer('max_alarm')->comment('максимальное значение аварии');
                $table->unsignedInteger('room')->nullable();
                $table->unsignedInteger('usensor_id')->nullable();
                $table->string('placetype',10)->nullable();
                $table->integer('port_SCL')->nullable();
                $table->integer('port_SDA')->nullable();



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
        Schema::dropIfExists('lightstats');
    }
}
