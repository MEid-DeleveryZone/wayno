<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProhibitedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prohibited_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('page_id');
            $table->string('image');
            $table->boolean('status')->default(true); // 0 for inactive, 1 for active
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
        Schema::dropIfExists('prohibited_items');
    }
}
