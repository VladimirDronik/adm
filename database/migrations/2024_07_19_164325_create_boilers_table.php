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
        Schema::create('boilers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('type', 50);
            $table->unsignedInteger('id_object')->nullable();
            $table->string('gateway_type', 50)->nullable();
            $table->unsignedBigInteger('gateway_id')->nullable();
            $table->string('protocol', 50);
            $table->string('mode', 50)->nullable();
            $table->string('heating_mode', 50)->nullable();
            $table->unsignedInteger('outdoor_sensor')->nullable();
            $table->unsignedInteger('indoor_sensor')->nullable();

            $table->foreign('id_object')
                ->references('id')
                ->on('objects')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('outdoor_sensor')
                ->references('id_object')
                ->on('termostats')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('indoor_sensor')
                ->references('id_object')
                ->on('termostats')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boilers');
    }
};
