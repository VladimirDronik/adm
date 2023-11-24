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
        Schema::table('boiler_gvs', function (Blueprint $table) {
            $table->renameColumn('pressue', 'pressure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boiler_gvs', function (Blueprint $table) {
            $table->renameColumn('pressure', 'pressue');
        });
    }
};
