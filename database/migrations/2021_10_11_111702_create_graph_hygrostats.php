<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraphHygrostats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('graph_hygrostats')) {
            Schema::create('graph_hygrostats', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('id_hygrostat')->comment('id гигрсотата из таблицы гигсростатов');
                $table->dateTime('datetime')->comment('дата и время значения');
                $table->double('value')->comment('значение параметра');

                $table->foreign('id_hygrostat')->references('id')->on('hygrostats')
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
        Schema::dropIfExists('graph_hygrostats');
    }
}
