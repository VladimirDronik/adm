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
                $table->string('name', 50)->default('')->after('id');
            });
        }

        if (Schema::hasTable('devusers')) {
            Schema::table('devusers', function (Blueprint $table) {
                $table->string('telegram_id', 30)->nullable();
            });
        }

        if (Schema::hasTable('devusers')) {
            Schema::table('devusers', function (Blueprint $table) {
                $table->string('push_id',255)->nullable();
            });
        }

        if (Schema::hasTable('devusers')) {
            Schema::table('devusers', function (Blueprint $table) {
                $table->string('phone_number',11)->nullable();
            });
        }

        if (Schema::hasTable('devusers')) {
            Schema::table('devusers', function (Blueprint $table) {
                $table->tinyInteger('telegram_send')->nullable();
            });
        }

        if (Schema::hasTable('devusers')) {
            Schema::table('devusers', function (Blueprint $table) {
                $table->tinyInteger('push_send')->nullable();
            });
        }

        if (Schema::hasTable('devusers')) {
            Schema::table('devusers', function (Blueprint $table) {
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
