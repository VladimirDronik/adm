<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulerTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduler_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('Название задачи');
            $table->unsignedInteger('object')->nullable()->comment('id объекта');
            $table->unsignedInteger('method')->nullable()->comment('id метода объекта');
            $table->unsignedInteger('script')->nullable()->comment('id скрипта. Выполняется, если объект не задан');
            $table->boolean('is_system')->default(false);
            $table->boolean('is_hidden')->default(false);

            $table->foreign('object')->references('id')->on('objects')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('method')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('script')->references('id')->on('scripts')
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
        Schema::dropIfExists('scheduler_tasks');
    }
}
