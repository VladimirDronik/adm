<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsIntoDevusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('devusers')) {

            Schema::table('devusers', function (Blueprint $table) {

                $table->string('name', 50)->after('id');
                $table->string('telegram_id', 30)->nullable();
                $table->string('push_id',255)->nullable();
                $table->string('phone_number',11)->nullable();
                $table->tinyInteger('telegram_send')->nullable();
                $table->tinyInteger('push_send')->nullable();
                $table->tinyInteger('sms_send')->nullable();

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
        //
    }
}
