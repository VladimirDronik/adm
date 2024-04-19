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
        Schema::table('conditioners', function (Blueprint $table) {
            $table->dropColumn(['temp']);
        });

        Schema::table('conditioners', function (Blueprint $table) {
            $table->dropForeign('conditioners_model_foreign');
            $table->dropForeign('conditioners_device_id_foreign');

            $table->dropColumn(['device_id', 'wb_mir', 'model', 'state', 'operation']);
            $table->dropTimestamps();

            $table->string('name')->after('id_object');
            $table->unsignedBigInteger('type')->after('name');
            $table->unsignedBigInteger('modbus_slaver_id')->after('type');
            $table->unsignedTinyInteger('temp')->nullable()->after('id_room');
            $table->unsignedInteger('id_room')->nullable()->change();
            $table->string('mode', 15)->nullable()->after('temp');
            $table->string('fan', 15)->nullable()->change();
            $table->string('vdir', 15)->nullable()->after('mode');
            $table->string('hdir', 15)->nullable()->after('vdir');

            $table->foreign('modbus_slaver_id')
                ->references('id')
                ->on('modbus_slavers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('type')
                ->references('id')
                ->on('conditioner_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conditioners', function (Blueprint $table) {
            $table->dropColumn(['temp']);
        });

        Schema::table('conditioners', function (Blueprint $table) {
            $table->dropForeign('conditioners_modbus_slaver_id_foreign');
            $table->dropForeign('conditioners_type_foreign');
            $table->dropColumn(['name', 'mode', 'vdir', 'hdir', 'modbus_slaver_id', 'type']);

            $table->unsignedInteger('device_id');
            $table->string('wb_mir');
            $table->unsignedInteger('model');
            $table->string('state', 3)->nullable();
            $table->decimal('temp', 5, 1)->nullable();
            $table->string('operation', 4)->nullable();
            $table->string('fan', 4)->nullable()->change();
            $table->timestamps();

            $table->foreign('model')
                ->references('id')
                ->on('conditioner_models')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
};
