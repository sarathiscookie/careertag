<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsUserexperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('userexperiences', function (Blueprint $table) {
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('postal', 50)->nullable();
            $table->string('search_string', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('userexperiences', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->dropColumn('country');
            $table->dropColumn('postal');
            $table->dropColumn('search_string');
        });
    }
}
