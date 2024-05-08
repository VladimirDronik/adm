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
            $table->boolean('is_system')->default(0);
        });

        Schema::table('termostats', function (Blueprint $table) {
            $table->boolean('is_system')->default(0);
        });

        Schema::table('hygrostats', function (Blueprint $table) {
            $table->boolean('is_system')->default(0);
        });

        Schema::table('pressurestats', function (Blueprint $table) {
            $table->boolean('is_system')->default(0);
        });

        Schema::table('carbdioxides', function (Blueprint $table) {
            $table->boolean('is_system')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lightstats', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });

        Schema::table('termostats', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });

        Schema::table('hygrostats', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });

        Schema::table('pressurestats', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });

        Schema::table('carbdioxides', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });
    }
};
