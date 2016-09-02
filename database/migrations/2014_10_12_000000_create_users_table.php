<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
            $table->integer('user_id')->primary();
            $table->string('user');
            $table->string('user_name');
            $table->string('user_email');
            $table->string('user_bio')->default('User has not written a bio.');
            $table->string('github_accesstoken');
            $table->text('github_url');
            $table->boolean('user_collab')->default(false);
            $table->boolean('user_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
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
