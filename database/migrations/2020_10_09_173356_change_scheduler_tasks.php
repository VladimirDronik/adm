<?php

/**
 * Меняем в таблице scheduler_tasks связь по ключу object, что бы при удалении объекта удалялось и само событие
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSchedulerTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('scheduler_tasks')) {
            Schema::table('scheduler_tasks', function (Blueprint $table) {
                $table->dropForeign(['object']);
            });
        }

        if (Schema::hasTable('scheduler_tasks')) {
            Schema::table('scheduler_tasks', function (Blueprint $table) {
                $table->foreign('object')->references('id')->on('objects')
                    ->onUpdate('cascade')->onDelete('cascade');
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
        //
    }
}
