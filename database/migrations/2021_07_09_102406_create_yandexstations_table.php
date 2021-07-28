<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYandexstationsTable extends Migration
{

    public function up()
    {
        Schema::create('yandexstations', function (Blueprint $table) {

            $table->increments('id');
            $table->string('speaker_id', 255)->nullable()->comment('ID колонки');
            $table->string('name', 255)->nullable()->comment('название колонки');
            $table->tinyInteger('volume')->nullable()->comment('громкость по умолчанию');
            $table->unsignedInteger('room')->nullable();
            $table->tinyInteger('active');

            $table->foreign('room')->references('id')->on('rooms')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yandexstations');
    }
}
