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
        Schema::table('cameras', function (Blueprint $table) {
            $table->unsignedBigInteger('recorder_id')->nullable()->after('link');
            $table->string('link', 255)->nullable()->change();
            $table->dropForeign('cameras_room_foreign');
            $table->dropColumn('room');
            $table->renameColumn('type', 'vendor');

            $table->foreign('recorder_id')
                ->references('id')
                ->on('recorders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('cameras', function (Blueprint $table) {
            $table->string('type', 20)->nullable()->after('vendor');
        });

        DB::statement('ALTER TABLE cameras ADD COLUMN base64_image MEDIUMBLOB AFTER image');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cameras', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('cameras', function (Blueprint $table) {
            $table->dropForeign('cameras_recorder_id_foreign');
            $table->dropColumn('recorder_id');
            $table->renameColumn('vendor', 'type');

            $table->unsignedInteger('room')->nullable()->after('link');
            $table->foreign('room')
                ->references('id')
                ->on('rooms')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        DB::statement('ALTER TABLE cameras DROP base64_image');
    }
};
