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
        Schema::table('lightstats', function (Blueprint $table) {
            $table->dropColumn(['placetype', 'port_SCL', 'port_SDA']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lightstats', function (Blueprint $table) {
            $table->string('placetype', 10)->nullable();
            $table->integer('port_SCL')->nullable();
            $table->integer('port_SDA')->nullable();
        });
    }
};
