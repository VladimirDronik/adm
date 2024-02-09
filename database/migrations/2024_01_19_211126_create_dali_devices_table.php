<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dali_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_object')->nullable();
            $table->string('name', 100);
            $table->unsignedInteger('type');
            $table->unsignedBigInteger('dali_gateway');
            $table->unsignedTinyInteger('address');
            $table->unsignedTinyInteger('failure')->comment('0 - ок, 1 - неисправность');
            $table->unsignedTinyInteger('brightness');
            $table->unsignedTinyInteger('is_cct');
            $table->unsignedSmallInteger('cct')->nullable();
            $table->unsignedInteger('room')->nullable();

            $table->foreign('id_object')
                ->references('id')
                ->on('objects')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('room')
                ->references('id')
                ->on('rooms')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('dali_gateway')
                ->references('id')
                ->on('modbus_slavers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dali_devices');
    }
};
