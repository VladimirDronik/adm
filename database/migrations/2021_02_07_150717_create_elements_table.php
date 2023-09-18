<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('elements')) {
            Schema::create('elements', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('type', 20);
                $table->unsignedInteger('id_object')->nullable();
                $table->string('handle', 30)->nullable();
                $table->string('image', 30)->nullable();
                $table->string('value', 255)->nullable();
                $table->unsignedInteger('page');
                $table->smallInteger('parent');
                $table->smallInteger('position');
                $table->tinyInteger('sort');
                $table->tinyInteger('active');

                $table->foreign('page')->references('id')->on('pages')
                    ->onUpdate('cascade')->onDelete('cascade');

                $table->foreign('id_object')->references('id')->on('objects')
                    ->onUpdate('cascade')->onDelete('set null');
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
