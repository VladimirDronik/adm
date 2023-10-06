<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('scripts')) {
            Schema::create('scripts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100)->comment('название скрипта');
                $table->string('link', 100)->comment('ссылка на скрипт в папке скрипты');
                $table->integer('count')->nullable()->comment('количество раз, которое выполнился скрипт');
                $table->boolean('system');
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
        Schema::dropIfExists('scripts');
    }
}
