<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvalidItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invalid_items', function (Blueprint $table) {
            $table->increments('invalid_item_id');
            $table->text('invalid_item_text');
            $table->text('invalid_item_error');
            $table->integer('scan_id');
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
        Schema::drop('invalid_items');
    }
}
