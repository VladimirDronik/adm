<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsRs485IntoCurtains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('curtains')) {
            Schema::table('curtains', function (Blueprint $table) {
                $table->unsignedTinyInteger('address')->nullable()->comment('адрес на шине RS485');
            });
        }

        if (Schema::hasTable('curtains')) {
            Schema::table('curtains', function (Blueprint $table) {
                $table->unsignedTinyInteger('group')->nullable()->comment('группа на шине RS485');
            });
        }

        if (Schema::hasTable('curtains')) {
            Schema::table('curtains', function (Blueprint $table) {
                $table->unsignedInteger('gw_id')->nullable()->comment('id шлюза ModbusTCP');
            });
        }

        if (Schema::hasTable('curtains')) {
            Schema::table('curtains', function (Blueprint $table) {
                $table->unsignedTinyInteger('percent')->nullable()->comment('процент открытия шторы');
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
        Schema::table('curtains', function (Blueprint $table) {
            $table->dropColumn(['address', 'group', 'gw_id', 'percent']);
        });
    }
}
