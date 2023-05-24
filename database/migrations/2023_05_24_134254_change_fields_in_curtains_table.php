<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

            $table->dropColumn('time');

            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->onUpdate('set null')
                ->onDelete('set null');
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
            $table->tinyInteger('time')->comment('время полного открытия или закрытия шторы');
            $table->dropForeign('curtains_device_id_foreign');
            $table->dropColumn('device_id');
        });
    }
}
