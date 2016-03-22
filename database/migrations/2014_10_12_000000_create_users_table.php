<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);										//why not firstname?
            $table->string('lastname', 50);
            $table->date('birthdate');
            $table->string('city', 50);
            $table->string('email')->unique();								// that is the "username" for login laravel right?
            $table->string('password', 60);
            $table->string('phone', 20);
            $table->integer('search_city_id')->index();						//foreign key cities.id
            $table->integer('search_circuit');								//its only an int with the miles / km radius around the city
            $table->date('search_begin');
            $table->enum('search_condition', ['fulltime','halftime']);		// this will be an selection of the working time conditions (fulltime, halftime.. maybe something else)
            $table->enum('privacy_image', ['show','hide']);					// should be clear.. thats all the privacy settings for tag categories or for the "tags" which are only text-fields
            $table->enum('privacy_tag_firstname', ['show','hide']);
            $table->enum('privacy_tag_lastname', ['show','hide']);
            $table->enum('privacy_tag_birthday', ['show','hide']);
            $table->enum('privacy_tag_city', ['show','hide']);
            $table->enum('privacy_tag_mail', ['show','hide']);
            $table->enum('privacy_tag_phone', ['show','hide']);
            $table->enum('privacy_cat_search', ['show','hide']);
            $table->enum('privacy_cat_languages', ['show','hide']);
            $table->enum('privacy_cat_ambition', ['show','hide']);
            $table->enum('privacy_cat_experience', ['show','hide']);
            $table->enum('privacy_cat_company', ['show','hide']);
            $table->enum('privacy_cat_interests', ['show','hide']);
            $table->integer('graph_1');										// value of the graph bars
            $table->integer('graph_2');
            $table->integer('graph_3');
            $table->integer('graph_4');
            $table->rememberToken();
            $table->timestamps()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
