<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionerKindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditioner_kinds', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('min');
            $table->tinyInteger('max');
            $table->decimal('precision', 5, 1);
            $table->json('operationModes');
            $table->json('fanModes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conditioner_kinds');
    }
}
