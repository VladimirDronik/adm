<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pressurestats', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type_sensor', 50);
            $table->unsignedInteger('id_object')->comment('id датчика давления из таблицы объектов');
            $table->double('current')->comment('текущее давление');
            $table->double('optimal')->comment('значение, которое должно быть в помещении');
            $table->double('gisteresis')->comment('гистерезис');
            $table->boolean('mode')->comment('0 - реакция на уменьшение давления, 1 - реакция на увеличение давления');
            $table->unsignedInteger('object')->nullable()->comment('Объект, у которого будем менять состояние');
            $table->unsignedInteger('method_on')->nullable()->comment('Метод объекта при срабатывании датчика давления на включение');
            $table->unsignedInteger('method_off')->nullable()->comment('Метод объекта при срабатывании датчика давления на выключение');
            $table->string('method_on_params', 100)->nullable();
            $table->string('method_off_params', 100)->nullable();
            $table->integer('min_threshold')->comment('минимальное значение, которое возможно в помещении');
            $table->integer('max_threshold')->comment('максимальное значение, которое возможно в помещении');
            $table->integer('min_alarm')->comment('минимальное значение аварии');
            $table->integer('max_alarm')->comment('максимальное значение аварии');
            $table->unsignedInteger('room')->nullable();
            $table->unsignedInteger('usensor_id');

            $table->foreign('id_object')->references('id')->on('objects')
                ->onUpdate('cascade')->onDelete('cascade');
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pressurestats');
    }
};
