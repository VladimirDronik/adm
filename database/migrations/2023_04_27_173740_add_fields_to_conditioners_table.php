<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToConditionersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('conditioners')) {
            Schema::table('conditioners', function (Blueprint $table) {
                $table->string('state', 3)->after('id_room')->nullable();
            });
        }

        if (Schema::hasTable('conditioners')) {
            Schema::table('conditioners', function (Blueprint $table) {
                $table->decimal('temp', 5, 1)->after('state')->nullable();
            });
        }

        if (Schema::hasTable('conditioners')) {
            Schema::table('conditioners', function (Blueprint $table) {
                $table->string('operation', 4)->after('temp')->nullable();
            });
        }

        if (Schema::hasTable('conditioners')) {
            Schema::table('conditioners', function (Blueprint $table) {
                $table->string('fan', 4)->after('operation')->nullable();
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
        Schema::table('conditioners', function (Blueprint $table) {
            $table->dropColumn(['state', 'temp', 'operation', 'fan']);
        });
    }
}
