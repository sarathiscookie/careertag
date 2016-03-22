<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAbilitytestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('abilitytests', function($table) {
            $table->dropColumn('question');
            $table->string('question_de', 255);
            $table->string('question_en', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('abilitytests', function($table) {
            $table->dropColumn('question_de');
            $table->dropColumn('question_en');
        });
    }
}
