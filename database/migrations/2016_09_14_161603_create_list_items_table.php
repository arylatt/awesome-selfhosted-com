<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_items', function (Blueprint $table) {
            $table->increments('list_item_id');
            $table->string('list_item_name');
            $table->text('list_item_url');
            $table->text('list_item_description');
            $table->text('list_item_demo');
            $table->text('list_item_sourcecode');
            $table->integer('list_item_license');
            $table->string('list_item_language');
            $table->integer('list_item_proprietary');
            $table->integer('header_id');
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
        Schema::drop('list_items');
    }
}
