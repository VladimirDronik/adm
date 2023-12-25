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
        Schema::create('modbus_registers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slaver_id');
            $table->string('name', 100);
            $table->string('register_type', 10);
            $table->unsignedInteger('starting_register');
            $table->unsignedInteger('registers_quantity');
            $table->string('data_format', 10);
            $table->string('units', 10)->nullable();
            $table->double('scale_unit')->nullable();
            $table->string('access', 2);
            $table->boolean('polling');
            $table->unsignedTinyInteger('polling_cycle')->nullable();
            $table->string('last_value')->nullable();
            $table->timestamp('timestamp', 3)->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();

            $table->foreign('slaver_id')
                ->references('id')
                ->on('modbus_slavers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modbus_registers');
    }
};
