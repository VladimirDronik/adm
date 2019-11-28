<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulerPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduler_points', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 1)->comment('w-недельные, m-месячные, y-годовые');
            $table->string('time', 5)->comment('время выполнения скрипта. для cron - количество минут (1, 5, 10, ..., 60)');
            $table->string('days', 50)->comment('дни выполнения скрипта. для cron - пустая строка. 0 - пн, 6 - вскр');
            $table->unsignedInteger('id_task')->comment('id задачи расписания');
            $table->boolean('close')->default(false)->comment('Если 1, то событие нельзя удалить');
            $table->boolean('system')->default(false)->comment('Если 1, то не показываем в событиях у клиента. При создании админом system = 0.');

            $table->foreign('id_task')->references('id')->on('scheduler_tasks')
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
        Schema::dropIfExists('scheduler_points');
    }
}
