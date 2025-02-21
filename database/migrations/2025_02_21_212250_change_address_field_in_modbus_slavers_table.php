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
        Schema::table('modbus_slavers', function (Blueprint $table) {
            $table->integer('address')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modbus_slavers', function (Blueprint $table) {
            $table->tinyInteger('address')->change();
        });
    }
};
