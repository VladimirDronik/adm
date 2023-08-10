<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOffMethodViewItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('view_items')) {
            Schema::table('view_items', function (Blueprint $table) {
                if (Schema::hasColumn('view_items', 'id_method')) {
                    $table->renameColumn('id_method', 'on_method');
                }
            });
        }

        if (Schema::hasTable('view_items')) {
            Schema::table('view_items', function (Blueprint $table) {
                if (!Schema::hasColumn('view_items', 'off_method')) {
                    $table->unsignedInteger('off_method')->nullable()->after('id_method');
                }
            });
        }

        if (Schema::hasTable('view_items')) {
            Schema::table('view_items', function (Blueprint $table) {
                if (!Schema::hasColumn('view_items', 'off_method')) {
                    $table->foreign('off_method')->references('id')->on('methods')
                        ->onUpdate('cascade')->onDelete('set null');
                }
            });
        }

        if (Schema::hasTable('view_items')) {
            Schema::table('view_items', function (Blueprint $table) {
                if (!Schema::hasColumn('view_items', 'off_method_params')) {
                    $table->string('off_method_params', 100)->nullable();
                }
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
