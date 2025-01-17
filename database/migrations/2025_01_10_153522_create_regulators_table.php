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
        Schema::create('regulators', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('object_id');
            $table->string('type');
            $table->string('source')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedInteger('room');
            $table->unsignedBigInteger('sensors_param_id');
            $table->double('setpoint');
            $table->double('hysteresis')->nullable();
            $table->unsignedInteger('lower_method')->nullable();
            $table->unsignedInteger('higher_method')->nullable();
            $table->unsignedInteger('fallback_method')->nullable();
            $table->double('min_setpoint');
            $table->double('max_setpoint');

            $table->foreign('object_id')
                ->references('id')
                ->on('objects')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('room')
                ->references('id')
                ->on('rooms')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('sensors_param_id')
                ->references('id')
                ->on('sensors_params')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('lower_method')
                ->references('id')
                ->on('methods')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('higher_method')
                ->references('id')
                ->on('methods')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('fallback_method')
                ->references('id')
                ->on('methods')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulators');
    }
};
