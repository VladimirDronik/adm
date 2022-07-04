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
        Schema::create('internal_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idElement')->nullable()->default(NULL);
            $table->string('type', 50)
                  ->charset('utf8mb4_unicode_ci')
                  ->nullable()
                  ->default(NULL);
        });
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
