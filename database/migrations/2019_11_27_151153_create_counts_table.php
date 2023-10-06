<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('counts')) {
            Schema::create('counts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('type', 10);
                $table->unsignedInteger('id_object')->nullable();
                $table->double('impulse', 10, 7);
                $table->string('unit', 6);
                $table->float('today_value');
                $table->float('total_value');

                $table->foreign('id_object')->references('id')->on('objects')
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
        Schema::dropIfExists('counts');
    }
}
