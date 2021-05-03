<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateAliceDevicesTable extends Migration
{
    public function up()
    {
        Schema::create('alice_devices', function (Blueprint $table) {


            $table->increments('id');
            $table->unsignedInteger('id_object')->nullable()->comment('объект, которым будем управлять');
            $table->string('name', 50)->comment('Название объекта управления, как он будет вызываться через Алису');
            $table->unsignedInteger('room')->nullable()->comment('помещение, в котором находится объект управления');
            $table->tinyInteger('active');

            $table->foreign('id_object')->references('id')->on('objects')
                ->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('alice_devices');
    }
}
