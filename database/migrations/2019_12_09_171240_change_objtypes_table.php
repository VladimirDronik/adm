<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeObjtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('objects')) {
            Schema::table('objects', function (Blueprint $table) {
                if (Schema::hasColumn('objects', 'type')) {
                    $table->string('type', 20)->change();
                }
            });
        }

        if (Schema::hasTable('objtypes')) {
            Schema::table('objtypes', function (Blueprint $table) {
                if (!Schema::hasColumn('objtypes', 'label')) {
                    $table->string('label', 30)->nullable();
                }

                if (Schema::hasColumn('objtypes', 'view_type')) {
                    $table->dropColumn('view_type');
                }

                if (Schema::hasColumn('objtypes', 'usestatus')) {
                    $table->dropColumn('usestatus');
                }

                if (Schema::hasColumn('objtypes', 'virt')) {
                    $table->dropColumn('virt');
                }

                if (Schema::hasColumn('objtypes', 'description')) {
                    $table->dropColumn('description');
                }
            });
        } else {
            Schema::create('objtypes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 20);
                $table->string('label', 30)->nullable();
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
        if (Schema::hasTable('objtypes')) {
            Schema::table('objtypes', function (Blueprint $table) {
                if (Schema::hasColumn('objtypes', 'label')) {
                    $table->dropColumn('label');
                }

                if (!Schema::hasColumn('objtypes', 'view_type')) {
                    $table->string('view_type', 20)->nullable();
                }

                if (!Schema::hasColumn('objtypes', 'usestatus')) {
                    $table->boolean('usestatus')->comment('используется ли смена состояния у объекта')
                        ->default(false);
                }

                if (!Schema::hasColumn('objtypes', 'virt')) {
                    $table->boolean('virt')->comment('виртуальный объект или реальный')
                        ->default(false);
                }

                if (!Schema::hasColumn('objtypes', 'description')) {
                    $table->text('description')->nullable();
                }
            });
        }
    }
}
