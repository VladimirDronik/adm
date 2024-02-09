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
            $table->string('comment')->nullable()->after('polling_cycle');
            $table->boolean('is_system')->default(0)->after('comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modbus_registers', function (Blueprint $table) {
            $table->dropColumn(['comment', 'is_system']);
        });
    }
};
