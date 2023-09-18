<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('ports')) {
            Schema::create('ports', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('id_device')->comment('id девайса из таблицы devices');
                $table->smallInteger('num_port')->comment('номер порта меги');
                $table->string('status', 3)->comment('статус порта in, out, ds, nc, 1w');
                $table->unsignedInteger('object')->nullable()->comment('id объекта (только для out)');
                $table->unsignedInteger('method')->nullable()->comment('id метода при одинарном нажатии');
                $table->unsignedInteger('dc_method')->nullable()->comment('id метода при двойном нажатии');
                $table->unsignedInteger('lc_method')->nullable()->comment('id метода при длительном нажатии');
                $table->string('comment')->comment('комментарий к порту');

                $table->foreign('id_device')->references('id')->on('devices')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('object')->references('id')->on('objects')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('method')->references('id')->on('methods')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('dc_method')->references('id')->on('methods')
                    ->onUpdate('cascade')->onDelete('set null');
                $table->foreign('lc_method')->references('id')->on('methods')
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
        Schema::dropIfExists('ports');
    }
}
