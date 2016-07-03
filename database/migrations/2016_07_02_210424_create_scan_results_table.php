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
            $table->string('result_title')
            $table->integer('scan_id');
            $table->integer('result_is_github');
            $table->timestamp('result_last_updated');
            $table->string('result_demo');
            $table->string('result_source');
            $table->string('result_description');
            $table->integer('license_id');
            $table->integer('language_id');
            $table->timestamps();

            $table->foreign('scan_id')->references('scan_id')->on('scans')->onDelete('cascade');
            $table->foreign('license_id')->references('license_id')->on('licenses')->onDelete('cascade');
            $table->foreign('language_id')->references('language_id')->on('languages')->onDelete('cascade');

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
