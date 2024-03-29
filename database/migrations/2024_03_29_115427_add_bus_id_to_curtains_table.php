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
        Schema::table('curtains', function (Blueprint $table) {
            $table->unsignedBigInteger('bus_id')->nullable()->after('device_id');

            $table->foreign('bus_id')
                ->references('id')
                ->on('modbus_buses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curtains', function (Blueprint $table) {
            $table->dropForeign('curtains_bus_id_foreign');
            $table->dropColumn('bus_id');
        });
    }
};
