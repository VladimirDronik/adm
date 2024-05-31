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
        Schema::drop('conditioner_codes');
        Schema::drop('conditioner_models');
        Schema::drop('conditioner_kinds');
        Schema::drop('conditioner_vendors');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('conditioner_kinds', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('min');
            $table->tinyInteger('max');
            $table->decimal('precision', 5, 1);
            $table->json('operationModes');
            $table->json('fanModes');
            $table->timestamps();
        });

        Schema::create('conditioner_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('kind');
            $table->string('status');
            $table->tinyInteger('temperature')->nullable();
            $table->string('operationMode')->nullable();
            $table->string('fanMode')->nullable();
            $table->text('code');
            $table->timestamps();

            $table->foreign('kind')
                ->references('id')
                ->on('conditioner_kinds')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('conditioner_vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('conditioner_models', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor');
            $table->string('name');
            $table->unsignedInteger('kind');
            $table->timestamps();

            $table->foreign('vendor')
                ->references('id')
                ->on('conditioner_vendors')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('kind')
                ->references('id')
                ->on('conditioner_kinds')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
};
