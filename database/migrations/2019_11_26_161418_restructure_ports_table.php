<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RestructurePortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ports', function (Blueprint $table) {
            $table->dropColumn('longclick');
            $table->dropColumn('doubleclick');
            $table->integer('dc_method')->after('method')->nullable()->comment('метод при двойном нажатии');
            $table->integer('lc_method')->after('dc_method')->nullable()->comment('метод при длительном нажатии');
        });

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
