<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeFieldsInCurtainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curtains', function (Blueprint $table) {
            $table->string('type', 20)
                ->comment('Тип: штора, жалюзи, рольставня')
                ->nullable()
                ->default(null)
                ->change();
        });

        Schema::table('curtains', function (Blueprint $table) {
            $table->unsignedInteger('device_id')->nullable();
        });

        Schema::table('curtains', function (Blueprint $table) {
            $table->dropColumn('gw_id');
        });

        Schema::table('curtains', function (Blueprint $table) {
            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->onUpdate('set null')
                ->onDelete('set null');
        });

        Schema::table('curtains', function (Blueprint $table) {
            $table->integer('time')
                ->comment('Время полного открытия или закрытия шторы')
                ->nullable()
                ->default(null)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curtains', function (Blueprint $table) {
            $table->string('type', 20)->comment('Тип: штора, жалюзи, рольставня')->change();
        });

        Schema::table('curtains', function (Blueprint $table) {
            $table->unsignedInteger('gw_id')->nullable()->comment('id шлюза ModbusTCP');
        });

        Schema::table('curtains', function (Blueprint $table) {
            $table->dropForeign('curtains_device_id_foreign');
        });

        Schema::table('curtains', function (Blueprint $table) {
            $table->dropColumn('device_id');
        });

        Schema::table('curtains', function (Blueprint $table) {
            $table->integer('time')->comment('Время полного открытия или закрытия шторы')->change();
        });
    }
}
