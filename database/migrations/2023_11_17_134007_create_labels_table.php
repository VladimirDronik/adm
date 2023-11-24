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
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_item');
            $table->unsignedInteger('id_object');
            $table->string('type_value', 30);

            $table->foreign('id_item')
                ->references('id')
                ->on('view_items')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('id_object')
                ->references('id')
                ->on('objects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labels');
    }
};
