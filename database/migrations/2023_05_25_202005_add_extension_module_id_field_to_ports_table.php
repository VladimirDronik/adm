<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtensionModuleIdFieldToPortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ports', function (Blueprint $table) {
            $table->unsignedInteger('extension_module_id')->nullable();

            $table->foreign('extension_module_id')
                ->references('id')
                ->on('extension_modules')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ports', function (Blueprint $table) {
            $table->dropForeign('ports_extension_module_id_foreign');
            $table->dropColumn('extension_module_id');
        });
    }
}
