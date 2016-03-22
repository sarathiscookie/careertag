<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfirmedAtUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->timestamp('confirmed_at')->nullable();
            $table->string('alias', 100);
            $table->dropColumn('name');
            $table->string('firstname', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('confirmed_at');
            $table->dropColumn('alias');
            $table->dropColumn('firstname');
        });
    }
}
