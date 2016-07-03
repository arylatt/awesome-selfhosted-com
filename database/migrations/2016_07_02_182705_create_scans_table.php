<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->increments('scan_id');
            $table->timestamp('scan_start');
            $table->timestamp('scan_end');
            $table->integer('scan_type'); //manual and scheduled
            $table->integer('scan_type'); //free and non-free
            $table->integer('scan_status'); //pending/progress, failed, success
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
        Schema::drop('scans');
    }
}
