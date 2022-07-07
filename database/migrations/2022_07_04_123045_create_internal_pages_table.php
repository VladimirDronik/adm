<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('internal_pages')) {
            Schema::create('internal_pages', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('idElement')->nullable()->default(NULL);
                $table->string('type', 50)
                      ->charset('utf8mb4')
                      ->nullable()
                      ->default(NULL);

                $table->foreign('idElement')->references('id')->on('elements')
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
        Schema::dropIfExists('internal_pages');
    }
}
