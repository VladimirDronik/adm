<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignIntoElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('elements')) {
            Schema::table('elements', function (Blueprint $table) {
                $table->foreign('id_object')->references('id')->on('objects')
                    ->onUpdate('cascade')->onDelete('set null');
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
        //
    }
}
