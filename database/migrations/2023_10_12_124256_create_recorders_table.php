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
        Schema::create('recorders', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('vendor', 255);
            $table->string('ip_address', 15);
            $table->string('login', 255);
            $table->string('password', 255);
            $table->unsignedTinyInteger('sort');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recorders');
    }
};
