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
        Schema::table('relays', function (Blueprint $table) {
            $table->unsignedBigInteger('gateway_id')->nullable()->after('id_object');
            $table->string('gateway_type', 50)->nullable()->after('gateway_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('relays', function (Blueprint $table) {
            $table->dropColumn(['gateway_id', 'gateway_type']);
        });
    }
};
