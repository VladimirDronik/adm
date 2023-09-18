<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGraphCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('graph_counts')) {
            Schema::create('graph_counts', function (Blueprint $table) {

                $table->date('datetime');
                $table->unsignedInteger('id_count');
                $table->float('value');

                $table->primary(['id_count', 'datetime']);

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
