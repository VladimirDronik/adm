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
        Schema::table('boiler', function (Blueprint $table) {
            $table->dropColumn('ip_address');

            $table->unsignedBigInteger('gateway_id')->nullable()->after('pressure');
            $table->string('gateway_type', 50)->nullable()->after('gateway_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boiler', function (Blueprint $table) {
            $table->string('ip_address', 15)->nullable()->after('pressure');

            $table->dropColumn(['gateway_id', 'gateway_type']);
        });
    }
};
