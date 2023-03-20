<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionerCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditioner_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('kind');
            $table->string('status');
            $table->tinyInteger('temperature')->nullable();
            $table->string('operationMode')->nullable();
            $table->string('fanMode')->nullable();
            $table->text('code');
            $table->timestamps();

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
        Schema::dropIfExists('conditioner_codes');
    }
}
