<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToUsensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usensors', function (Blueprint $table) {
            $table->string('type')->nullable();

            $table->double('atm_pressure')
                ->nullable()
                ->comment('текущий уровень атмосферного давления')
                ->after('lux');
            $table->double('co2')
                ->nullable()
                ->comment('текущий уровень co2')
                ->after('atm_pressure');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usensors', function (Blueprint $table) {
            $table->dropColumn(['type', 'atm_pressure', 'co2']);
        });
    }
}
