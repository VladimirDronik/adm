<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHiteprodevTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hiteprodev', function (Blueprint $table) {
            $table->integer('id');
            $table->string('type', 20);
            $table->string('name', '100');
            $table->string('status',200);

            $table->unsignedInteger('id_object')->nullable();

            $table->foreign('id_object')->references('id')->on('objects')
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
        Schema::dropIfExists('hiteprodev');
    }
}
