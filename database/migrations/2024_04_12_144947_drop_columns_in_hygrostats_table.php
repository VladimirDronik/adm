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
        Schema::table('hygrostats', function (Blueprint $table) {
            $table->dropColumn(['subdev_id', 'placetype']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hygrostats', function (Blueprint $table) {
            $table->integer('subdev_id')->nullable();
            $table->string('placetype', 10)->nullable();
        });
    }
};
