<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionerModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditioner_models', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor');
            $table->string('name');
            $table->unsignedInteger('kind');
            $table->timestamps();

            $table->foreign('vendor')
                ->references('id')
                ->on('conditioner_vendors')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('kind')
                ->references('id')
                ->on('conditioner_kinds')
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
        Schema::dropIfExists('conditioner_models');
    }
}
