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
        Schema::create('sensor_graphs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('param_id');
            $table->dateTime('datetime');
            $table->double('value')->nullable();

            $table->foreign('param_id')
                ->references('id')
                ->on('sensors_params')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_graphs');
    }
};
