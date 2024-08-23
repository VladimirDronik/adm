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
        Schema::dropIfExists('boiler');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('boiler', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('protocol', 30);
            $table->unsignedInteger('id_object')->nullable();
            $table->string('mode', 6)->nullable();
            $table->tinyInteger('burner')->nullable();
            $table->tinyInteger('burner_GVS')->nullable();
            $table->tinyInteger('burner_modulation')->nullable();
            $table->tinyInteger('pump_status')->nullable();
            $table->double('pressure', 10, 1, true)->nullable();
            $table->unsignedBigInteger('gateway_id')->nullable()->after('pressure');
            $table->string('gateway_type', 50)->nullable()->after('gateway_id');
            $table->boolean('thermostat');
            $table->boolean('boiler');
            $table->boolean('lock');
            $table->tinyInteger('water_temp')->nullable();
            $table->tinyInteger('gvssupply')->nullable();
            $table->tinyInteger('gvsreturn')->nullable();
            $table->tinyInteger('csupply')->nullable();
            $table->tinyInteger('creturn')->nullable();
            $table->tinyInteger('target_heat_temp')->nullable();
            $table->tinyInteger('target_water_temp')->nullable();
            $table->integer('id_outside_thermostat')->nullable();
            $table->string('error_code', 10)->nullable();

            $table->foreign('id_object')
                ->references('id')
                ->on('objects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
};
