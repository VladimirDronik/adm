<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualsdevTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtualsdev', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->unsignedInteger('id_object')->nullable();
            $table->unsignedInteger('method_on')->nullable();
            $table->unsignedInteger('method_off')->nullable();

            $table->foreign('id_object')->references('id')->on('objects')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('method_on')->references('id')->on('methods')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('method_off')->references('id')->on('methods')
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
        Schema::dropIfExists('virtualsdev');
    }
}
