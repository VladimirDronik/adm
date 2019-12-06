<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraphCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('graph_counts')) {
            Schema::create('graph_counts', function (Blueprint $table) {

                $table->date('date');
                $table->unsignedInteger('id_count');
                $table->mediumInteger('value');

                $table->primary(['id_count', 'date']);

                $table->foreign('id_count')->references('id')->on('counts')
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('graph_counts');
    }
}
