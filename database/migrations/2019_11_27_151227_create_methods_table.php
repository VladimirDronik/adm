<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('methods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_object');
            $table->string('name', 100)->comment('Название метода объекта');
            $table->string('easy', 20)->nullable()->comment('выполнение простого действия (например переключение порта). В значениях указываем номер порта устройства');
            $table->unsignedInteger('script')->nullable()->comment('id скрипта из таблицы скриптов');
            $table->string('comment');


            $table->foreign('id_object')->references('id')->on('objects')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('script')->references('id')->on('scripts')
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
        Schema::dropIfExists('methods');
    }
}
