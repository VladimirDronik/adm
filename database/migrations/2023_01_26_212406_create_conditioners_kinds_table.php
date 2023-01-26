<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionersKindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditioners_kinds', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('min');
            $table->tinyInteger('max');
            $table->decimal('precision'); // Сколько знаков после запятой делать
            $table->string('operationModes');
            $table->string('fanModes');
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
        Schema::dropIfExists('conditioners_kinds');
    }
}
