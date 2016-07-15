<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePullRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pull_requests', function (Blueprint $table) {
            $table->integer('pr_id');
            $table->integer('pr_author');
            $table->integer('pr_status')->default(0);
            $table->string('pr_title');
            $table->string('pr_url');
            $table->integer('pr_mergeable');
            $table->integer('pr_locked')->default(0);
            $table->timestamps();

            $table->primary('pr_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pull_requests');
    }
}
