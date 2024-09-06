<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToYandexstationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yandexstations', function (Blueprint $table) {
            $table->string('scenario_id')->nullable();
            $table->string('platform')->nullable();
            $table->string('device_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yandexstations', function (Blueprint $table) {
            $table->dropColumn(['scenario_id', 'platform', 'device_id']);
        });
    }
}
