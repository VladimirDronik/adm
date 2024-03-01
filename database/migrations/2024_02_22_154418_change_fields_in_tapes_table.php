<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tapes', function (Blueprint $table) {
            $table->dropColumn('status');

            $table->smallInteger('channel')->nullable()->after('type');
            $table->smallInteger('cct')->nullable();
            $table->unsignedBigInteger('controller_id')->nullable()->after('id_object');
            $table->unsignedInteger('room')->nullable()->after('controller_id');

            $table->foreign('controller_id')
                ->references('id')
                ->on('modbus_slavers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('room')
                ->references('id')
                ->on('rooms')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tapes', function (Blueprint $table) {
            $table->string('status', 3);

            $table->dropForeign(['tapes_controller_id_foreign', 'tapes_room_foreign']);
            $table->dropColumn(['channel', 'controller_id', 'cct', 'room']);
        });
    }
};
