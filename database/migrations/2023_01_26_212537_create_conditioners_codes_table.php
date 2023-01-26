<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionersCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditioners_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('kind');
            $table->string('status');
            $table->tinyInteger('temperature')->nullable();
            $table->string('operationMode')->nullable();
            $table->string('fanMode')->nullable();
            $table->string('code');
            $table->timestamps();

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
        Schema::dropIfExists('conditioners_codes');
    }
}
