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
        Schema::create('sensors_params', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('object_id');
            $table->string('param');
            $table->string('name');
            $table->string('get_param')->nullable();
            $table->double('value')->nullable();
            $table->string('units')->nullable();
            $table->unsignedTinyInteger('accuracy');
            $table->boolean('graph');
            $table->double('min_range')->nullable();
            $table->double('max_range')->nullable();
            $table->double('min_alarm')->nullable();
            $table->double('max_alarm')->nullable();
            $table->dateTime('timestamp');

            $table->foreign('object_id')
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
        Schema::dropIfExists('sensors_params');
    }
};
