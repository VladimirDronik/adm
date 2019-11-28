<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('scenes')) {
            Schema::create('scenes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('label', 150);
                $table->string('image', 20);
                $table->string('background_color', 7);
                $table->tinyInteger('sort');
                $table->boolean('active')->default(false);
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
        Schema::dropIfExists('scenes');
    }
}
