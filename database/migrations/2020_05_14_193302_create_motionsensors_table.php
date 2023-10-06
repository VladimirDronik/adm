<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotionsensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motionsensors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_object')->nullable()->comment('id датчика из таблицы объектов');
            $table->string('name');
            $table->unsignedInteger('method_normal')->nullable()->comment('Метод при нормальном режиме');
            $table->unsignedInteger('method_eco')->nullable()->comment('Метод при эко режиме');
            $table->unsignedInteger('method_night')->nullable()->comment('Метод при ночном режиме');
            $table->unsignedInteger('method_morning')->nullable()->comment('Метод при утреннем режиме');
            $table->unsignedInteger('method_evening')->nullable()->comment('Метод при вечернем режиме');
            $table->unsignedInteger('method_guard')->nullable()->comment('Метод при режиме охраны');
            $table->unsignedInteger('lightstat')->nullable()->comment('Светостат с которым сравнивается значение');
            $table->string('equality', 1)->nullable()->comment('Знак сравнения значения');
            $table->unsignedInteger('lightvalue')->nullable()->comment('Значение с которым сравниваем значение светостата');
            $table->unsignedInteger('method_light')->nullable()->comment('Метод при пороговом значении освещенности');

            $table->foreign('id_object')->references('id')->on('objects')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('method_normal')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('method_eco')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('method_night')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('method_morning')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('method_evening')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('method_guard')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('method_light')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('lightstat')->references('id')->on('lightstats')
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
        Schema::dropIfExists('motionsensors');
    }
}
