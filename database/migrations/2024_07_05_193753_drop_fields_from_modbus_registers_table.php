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
        Schema::table('modbus_registers', function (Blueprint $table) {
            $table->dropColumn(['polling', 'polling_cycle']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modbus_registers', function (Blueprint $table) {
            $table->boolean('polling')->default(0);
            $table->unsignedTinyInteger('polling_cycle')->nullable();
        });
    }
};
