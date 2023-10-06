<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteGraphHumiditiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('graph_humidities');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('graph_humidities')) {
            Schema::create('graph_humidities', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('id_count')->comment('id датчика влажности');
                $table->dateTime('datetime')->comment('дата и время значения');
                $table->unsignedTinyInteger('value')->comment('значение параметра в процентах (от 0 до 100 вкл)');
            });
        }
    }
}
