<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoumnIntoViewItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('view_items')) {
            Schema::table('view_items', function (Blueprint $table) {
                $table->string('params', 255)->nullable();
            });
        }

        if (Schema::hasTable('view_items')) {
            Schema::table('view_items', function (Blueprint $table) {
                $table->string('color', 20)->nullable();
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
