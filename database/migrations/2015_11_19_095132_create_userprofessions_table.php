<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserprofessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userprofessions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('graduation_id')->unsigned();
            $table->integer('experience_id');
            $table->decimal('grade', 2, 1);			//grade from 1.0 to 4.0

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('graduation_id')->references('id')->on('graduations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('userprofessions');
    }
}
