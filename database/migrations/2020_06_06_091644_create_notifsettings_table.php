<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifsettings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->nullable();
            $table->string('type', 20)->nullable();
            $table->tinyInteger('priority')->nullable();
            $table->string('message')->nullable();
            $table->tinyInteger('text_flag')->nullable();
            $table->tinyInteger('sound_flag')->nullable();
            $table->unsignedInteger('id_sound')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifsettings');
    }
}
