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
        Schema::create('graph_pressures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_count')->comment('id датчика давления');
            $table->dateTime('datetime')->comment('дата и время значения');
            $table->double('value')->comment('значение параметра');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graph_pressures');
    }
};
