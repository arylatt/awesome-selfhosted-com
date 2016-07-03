<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScanResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('result_id');
            $table->string('result_title');
            $table->integer('scan_id')->unsigned();
            $table->integer('result_is_github');
            $table->timestamp('result_last_updated');
            $table->string('result_demo');
            $table->string('result_source');
            $table->string('result_description');
            $table->integer('license_id')->unsigned();
            $table->integer('language_id')->unsigned();
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
        Schema::drop('results');
    }
}
