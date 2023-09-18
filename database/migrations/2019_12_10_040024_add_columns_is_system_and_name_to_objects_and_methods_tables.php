<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIsSystemAndNameToObjectsAndMethodsTables extends Migration
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
                if (! Schema::hasColumn('objects', 'is_system')) {
                    $table->boolean('is_system')->default(false);
                }
            });
        }

        if (Schema::hasTable('methods')) {
            Schema::table('methods', function (Blueprint $table) {
                if (! Schema::hasColumn('methods', 'is_system')) {
                    $table->boolean('is_system')->default(false);
                }
            });
        }

        Schema::table('termostats', function (Blueprint $table) {
            if (! Schema::hasColumn('termostats', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('objects')) {
            Schema::table('objects', function (Blueprint $table) {
                if (Schema::hasColumn('objects', 'is_system')) {
                    $table->dropColumn('is_system');
                }
            });
        }

        if (Schema::hasTable('methods')) {
            Schema::table('methods', function (Blueprint $table) {
                if (Schema::hasColumn('methods', 'is_system')) {
                    $table->dropColumn('is_system');
                }
            });
        }

        Schema::table('termostats', function (Blueprint $table) {
            if (Schema::hasColumn('termostats', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
}
