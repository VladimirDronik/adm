<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionersModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditioners_models', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('vendor');
            $table->string('name');
            $table->unsignedBigInteger('kind');
            $table->timestamps();

            $table->foreign('vendor')
                ->references('id')
                ->on('conditioners_vendors')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('kind')
                ->references('id')
                ->on('conditioners_kinds')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conditioners_models');
    }
}
