<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeViewItemsTable extends Migration
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
                if (Schema::hasColumn('view_items', 'type_name')) {
                    $table->renameColumn('type_name', 'type');
                }

                if (Schema::hasColumn('view_items', 'name')) {
                    $table->dropColumn('name');
                }

                if (Schema::hasColumn('view_items', 'on_image')) {
                    $table->renameColumn('on_image', 'icon');
                }

                if (Schema::hasColumn('view_items', 'on_title')) {
                    $table->renameColumn('on_title', 'title');
                }

                if (Schema::hasColumn('view_items', 'off_image')) {
                    $table->dropColumn('off_image');
                }

                if (Schema::hasColumn('view_items', 'off_title')) {
                    $table->dropColumn('off_title');
                }
            });

            Schema::table('view_items', function (Blueprint $table) {
                $table->string('type', 20)->change();
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
