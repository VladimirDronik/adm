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
        Schema::create('dali_device_group', function (Blueprint $table) {
            $table->unsignedInteger('dali_device_id');
            $table->unsignedInteger('group_id');

            $table->foreign('dali_device_id')
                ->references('id_object')
                ->on('dali_devices')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('group_id')
                ->references('id_object')
                ->on('dali_devices')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dali_device_group');
    }
};
