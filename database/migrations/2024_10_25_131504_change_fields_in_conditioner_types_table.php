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
        Schema::table('conditioner_types', function (Blueprint $table) {
            $table->json('temperature')->nullable()->change();
            $table->json('mode')->nullable()->change();
            $table->json('fan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conditioner_types', function (Blueprint $table) {
            $table->json('temperature')->nullable(false)->change();
            $table->json('mode')->nullable(false)->change();
            $table->json('fan')->nullable(false)->change();
        });
    }
};
