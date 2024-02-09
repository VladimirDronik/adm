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
        Schema::create('modbus_slavers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedBigInteger('type');
            $table->unsignedTinyInteger('address');
            $table->unsignedBigInteger('bus');
            $table->boolean('active')->default(0);

            $table->foreign('bus')
                ->references('id')
                ->on('modbus_buses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('type')
                ->references('id')
                ->on('modbus_slavers_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modbus_slavers');
    }
};
