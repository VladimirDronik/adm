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
            $table->unsignedInteger('dali_gateway');
            $table->unsignedTinyInteger('address');
            $table->unsignedTinyInteger('failure')->comment('0 - ок, 1 - неисправность');
            $table->unsignedTinyInteger('brightness');
            $table->unsignedTinyInteger('is_cct');
            $table->unsignedSmallInteger('cct')->nullable();
            $table->unsignedInteger('room');

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
