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
        Schema::create('graph_carbdioxides', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_carbdioxide')->comment('id датчика углекислого газа из таблицы carbdioxides');
            $table->dateTime('datetime')->comment('дата и время значения');
            $table->double('value')->comment('значение параметра');

            $table->foreign('id_carbdioxide')->references('id')->on('carbdioxides')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graph_carbdioxides');
    }
};
