<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tagcategory_id')->unsigned();
            $table->string('title_de', 100);
            $table->string('title_en', 100);
            $table->enum('suggestion', ['no','yes']);									// this is an switch which says that tag can be suggested to other customers. the client will maintain it
            $table->integer('created_by');												// to know which customer created this tag .. not for linking .. there will be several initial tags from the client. then this id = 0
            $table->timestamps()->useCurrent();

            $table->foreign('tagcategory_id')->references('id')->on('tagcategories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tags');
    }
}
