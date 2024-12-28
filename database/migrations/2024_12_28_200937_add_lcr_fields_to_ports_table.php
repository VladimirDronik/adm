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
        Schema::table('ports', function (Blueprint $table) {
            $table->unsignedInteger('lcr_method')->nullable()->after('lc_method');
            $table->string('lcr_method_params', 100)->nullable()->after('lc_method_params');

            $table->foreign('lcr_method')
                ->references('id')
                ->on('methods')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ports', function (Blueprint $table) {
            $table->dropForeign('ports_lcr_method_foreign');
            $table->dropColumn(['lcr_method', 'lcr_method_params']);
        });
    }
};
