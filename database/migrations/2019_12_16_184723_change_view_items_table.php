<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
                $deleteColumns = [];

                if (Schema::hasColumn('view_items','name')) {
                    $deleteColumns[] = 'name';
                }
                if (Schema::hasColumn('view_items','off_image')) {
                    $deleteColumns[] = 'off_image';
                }
                if (Schema::hasColumn('view_items','off_title')) {
                    $deleteColumns[] = 'off_title';
                }

                if (!empty($deleteColumns)) {
                    $table->dropColumn($deleteColumns);
                }
            });
        }

        if (Schema::hasTable('view_items')) {
            Schema::table('view_items', function (Blueprint $table) {
                if (Schema::hasColumn('view_items', 'type_name')) {
                    $table->renameColumn('type_name', 'type');
                }
            });
        }

        if (Schema::hasTable('view_items')) {
            Schema::table('view_items', function (Blueprint $table) {
                if (Schema::hasColumn('view_items', 'on_image')) {
                    $table->renameColumn('on_image', 'icon');
                }
            });
        }

        if (Schema::hasTable('view_items')) {
            Schema::table('view_items', function (Blueprint $table) {
                if (Schema::hasColumn('view_items', 'on_title')) {
                    $table->renameColumn('on_title', 'title');
                }
            });
        }

        if (Schema::hasTable('view_items')) {
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
