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
        Schema::table('elements', function (Blueprint $table) {
            $table->dropColumn(['image', 'value']);

            $table->string('status')->nullable();
            $table->boolean('settings')->nullable();
            $table->string('wh_color')->nullable();
            $table->string('bl_color')->nullable();
            $table->string('units', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elements', function (Blueprint $table) {
            $table->string('image', 30)->nullable();
            $table->string('value')->nullable();

            $table->dropColumn(['status', 'settings', 'wh_color', 'bl_color', 'units']);
        });
    }
};
