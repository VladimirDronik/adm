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
        Schema::create('conditioner_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('device');
            $table->json('temperature');
            $table->json('mode');
            $table->json('fan');
            $table->json('vdir')->nullable();
            $table->json('hdir')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conditioner_types');
    }
};
