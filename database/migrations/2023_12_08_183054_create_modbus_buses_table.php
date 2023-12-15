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
        Schema::create('modbus_buses', function (Blueprint $table) {
            $table->id();
            $table->string('device', 100);
            $table->string('type', 3);
            $table->unsignedInteger('baudrate')->nullable();
            $table->unsignedTinyInteger('length')->nullable();
            $table->string('parity', 10)->nullable();
            $table->unsignedTinyInteger('stopbits')->nullable();
            $table->string('ip_address', 15)->nullable();
            $table->unsignedSmallInteger('port')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modbus_buses');
    }
};
