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
        Schema::table('boiler', function (Blueprint $table) {
            $table->tinyInteger('gvsreturn')->nullable()->after('feed_water_temp');
            $table->string('error_code', 10)->nullable();

            $table->renameColumn('feed_heat_temp', 'csupply');
            $table->renameColumn('back_heat_temp', 'creturn');
            $table->renameColumn('feed_water_temp', 'gvssupply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boiler', function (Blueprint $table) {
            $table->dropColumn(['gvsreturn', 'error_code']);

            $table->renameColumn('csupply', 'feed_heat_temp');
            $table->renameColumn('creturn', 'back_heat_temp');
            $table->renameColumn('gvssupply', 'feed_water_temp');
        });
    }
};
