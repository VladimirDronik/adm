<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnActiveSchedulerTasksTable extends Migration
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
                if (! Schema::hasColumn('scheduler_tasks', 'active')) {
                    $table->boolean('active')->default(true);
                }
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
        if (Schema::hasTable('scheduler_tasks')) {
            Schema::table('scheduler_tasks', function (Blueprint $table) {
                if (Schema::hasColumn('scheduler_tasks', 'active')) {
                    $table->dropColumn('active');
                }
            });
        }
    }
}
