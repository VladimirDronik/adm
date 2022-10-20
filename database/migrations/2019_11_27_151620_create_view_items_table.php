<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('view_items')) {
            Schema::create('view_items', function (Blueprint $table) {
                $table->increments('id');
                $table->string('type_name', 8)->comment('тип элемента: button, switch, temp, humidity, info');
                $table->string('name', 100)->comment('название элемента на русском языке');
                $table->string('description')->comment('описание элемента на русском языке');
                $table->string('status', 150);
                $table->unsignedInteger('id_object')->nullable()->comment('id объекта из таблицы объектов');
                $table->unsignedInteger('id_method')->nullable()->comment('метод объекта из таблицы методов');
                $table->string('on_image', 20);
                $table->string('off_image', 20)->nullable();
                $table->string('on_title', 50)->nullable();
                $table->string('off_title', 50)->nullable();
                $table->smallInteger('position_left')->nullable();
                $table->smallInteger('position_top')->nullable();
                $table->unsignedInteger('room')->nullable();
                $table->unsignedInteger('scene')->nullable();
                $table->tinyInteger('sort');
                $table->boolean('active');

                $table->foreign('id_object')->references('id')->on('objects')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('id_method')->references('id')->on('methods')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('room')->references('id')->on('rooms')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('scene')->references('id')->on('scenes')
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
        Schema::dropIfExists('view_items');
    }
}
