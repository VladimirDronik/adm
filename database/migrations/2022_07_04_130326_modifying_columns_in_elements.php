<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyingColumnsInElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Scheme::table('elements', function(Blueprint $table) {
            /* Добавление столбцов */
            $table->unsignedInteger('id_object')->nullable()->default(NULL);

            $table->string('handle', 30)
                  ->charset('utf8mb4_unicode_ci')
                  ->nullable()
                  ->default(NULL);

            /* Изменение столбцов */
            $table->string('image', 30)
                  ->charset('utf8mb4_unicode_ci')
                  ->nullable()
                  ->default(NULL)
                  -change();

            $table->string('value', 255)
                  ->charset('utf8mb4_unicode_ci')
                  ->nullable()
                  ->default(NULL)
                  -change();
        });
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
