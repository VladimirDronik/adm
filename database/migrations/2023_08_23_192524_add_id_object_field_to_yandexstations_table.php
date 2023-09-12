<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdObjectFieldToYandexstationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yandexstations', function (Blueprint $table) {
            $table->unsignedInteger('id_object')->nullable()->after('id');

            $table->foreign('id_object')
                ->references('id')
                ->on('objects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yandexstations', function (Blueprint $table) {
            $table->dropForeign('yandexstations_id_object_foreign');
        });

        Schema::table('yandexstations', function (Blueprint $table) {
            $table->dropColumn('id_object');
        });
    }
}
