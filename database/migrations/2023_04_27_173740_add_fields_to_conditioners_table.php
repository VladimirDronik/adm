<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
                $table->decimal('temp', 5, 1)->after('state')->nullable();
                $table->string('operation', 4)->after('temp')->nullable();
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
            $table->dropColumn('state');
            $table->dropColumn('temp');
            $table->dropColumn('operation');
            $table->dropColumn('fan');
        });
    }
}
