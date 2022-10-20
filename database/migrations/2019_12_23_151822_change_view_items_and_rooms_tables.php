<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeViewItemsAndRoomsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('view_items', function (Blueprint $table) {
            if (!Schema::hasColumn('view_items', 'room_group')) {
                $table->unsignedInteger('room_group')->nullable();

                $table->foreign('room_group')->references('id')->on('rooms')
                    ->onUpdate('cascade')->onDelete('set null');
            }
        });

        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'group_room')) {
                $table->unsignedInteger('group_room')->nullable();
            }
            if (!Schema::hasColumn('rooms', 'is_group')) {
                $table->boolean('is_group')->default(false);
            }
        });
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
