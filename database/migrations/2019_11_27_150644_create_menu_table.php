<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('menu')) {
            Schema::create('menu', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 20);
                $table->string('title', 20);
                $table->string('link', 20)->nullable();
                $table->string('image', 20);
                $table->tinyInteger('sort');
                $table->boolean('active');

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
        Schema::dropIfExists('menu');
    }
}
