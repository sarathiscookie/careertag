<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserabilitytestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userabilitytests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('abilitytest_id')->unsigned();
            $table->integer('points');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('abilitytest_id')->references('id')->on('abilitytests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('userabilitytests');
    }
}
