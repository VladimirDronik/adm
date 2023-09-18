<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIntoPorts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ports', function (Blueprint $table) {
            if (! Schema::hasColumn('ports', 'type')) {
                $table->string('type', 3)->after('num_port')->comment('тип порта контроллера');
            }
        });

        Schema::table('ports', function (Blueprint $table) {
            $table->string('status', 8)->change();
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
