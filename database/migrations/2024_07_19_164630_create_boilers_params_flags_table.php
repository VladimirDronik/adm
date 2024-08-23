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
        Schema::create('boilers_params_flags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('boiler_id');
            $table->boolean('modulation');
            $table->boolean('pressure');
            $table->boolean('ch_current_temp');
            $table->boolean('ch_setpoint_temp');
            $table->boolean('dhw_current_temp');
            $table->boolean('dhw_setpoint_temp');
            $table->boolean('return_temp');
            $table->boolean('error_code');
            $table->boolean('outdoor_temp');
            $table->boolean('indoor_temp');

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
        Schema::dropIfExists('boilers_params_flags');
    }
};
