<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

            $table->unsignedInteger('device_id')->nullable();

            $table->dropColumn('gw_id');

            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->onUpdate('set null')
                ->onDelete('set null');
        });

        DB::statement("ALTER TABLE curtains MODIFY time TINYINT NULL DEFAULT NULL COMMENT 'время полного открытия или закрытия шторы'");
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
            $table->unsignedInteger('gw_id')->nullable()->comment('id шлюза ModbusTCP');
            $table->dropForeign('curtains_device_id_foreign');
            $table->dropColumn('device_id');
        });

        DB::statement("ALTER TABLE curtains MODIFY time TINYINT NOT NULL COMMENT 'время полного открытия или закрытия шторы'");
    }
}
