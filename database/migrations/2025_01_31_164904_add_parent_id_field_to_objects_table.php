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
        Schema::table('objects', function (Blueprint $table) {
            $table->unsignedInteger('parent_id')->nullable()->after('is_system');

            $table->foreign('parent_id')
                ->references('id')
                ->on('objects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objects', function (Blueprint $table) {
            $table->dropForeign('objects_parent_id_foreign');
            $table->dropColumn('parent_id');
        });
    }
};
