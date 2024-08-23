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
        Schema::create('boilers_params', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('boiler_id');
            $table->unsignedInteger('modulation')->nullable();
            $table->unsignedDouble('pressure')->nullable();
            $table->unsignedInteger('ch_current_temp')->nullable();
            $table->unsignedInteger('ch_setpoint_temp')->nullable();
            $table->unsignedInteger('dhw_current_temp')->nullable();
            $table->unsignedInteger('dhw_setpoint_temp')->nullable();
            $table->unsignedInteger('return_temp')->nullable();
            $table->string('error_code', 10)->nullable();

            $table->foreign('boiler_id')
                ->references('id')
                ->on('boilers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boilers_params');
    }
};
