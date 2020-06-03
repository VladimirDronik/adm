<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldIntoDrycintacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('drycontacts')) {

            Schema::table('drycontacts', function (Blueprint $table) {


                $table->unsignedInteger('method_on')->nullable();
                $table->unsignedInteger('method_off')->nullable();
                $table->unsignedInteger('param_method_on')->nullable();
                $table->unsignedInteger('param_method_off')->nullable();

                $table->foreign('method_on')->references('id')->on('methods')
                    ->onUpdate('cascade')->onDelete('set null');

                $table->foreign('method_off')->references('id')->on('methods')
                    ->onUpdate('cascade')->onDelete('set null');
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
