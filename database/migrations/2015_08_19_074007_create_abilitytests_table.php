<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbilitytestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abilitytests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('target')->unsigned();					// possible values 1-4 for the 4 different graphbars to know which question collect points for which graph bar.
            $table->string('question', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('abilitytests');
    }
}
