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
        Schema::create('regulator_graphs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('regulator_id');
            $table->string('param');
            $table->dateTime('datetime');
            $table->string('value');

            $table->foreign('regulator_id')
                ->references('id')
                ->on('regulators')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulator_graphs');
    }
};
