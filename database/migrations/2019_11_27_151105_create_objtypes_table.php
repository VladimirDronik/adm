<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('objtypes')) {
            Schema::create('objtypes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 20);
                $table->string('view_type', 20);
                $table->boolean('usestatus')->comment('используется ли смена состояния у объекта');
                $table->boolean('virt')->comment('виртуальный объект или реальный');
                $table->text('description');
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
        Schema::dropIfExists('objtypes');
    }
}
