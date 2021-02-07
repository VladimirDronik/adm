<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('elements')) {
            Schema::create('elements', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('type', 20);
                $table->string('image', 20);
                $table->string('value', 255);
                $table->unsignedInteger('page');
                $table->smallInteger('parent');
                $table->smallInteger('position');
                $table->tinyInteger('sort');
                $table->tinyInteger('active');

                $table->foreign('page')->references('id')->on('pages')
                    ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('elements');
    }
}
