<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createreviewfilestable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_rating_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_product_rating_id')->unsigned();
            $table->string('file', 500)->nullable();
            $table->foreign('order_product_rating_id')->references('id')->on('order_product_ratings')->onDelete('cascade');
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
        Schema::dropIfExists('order_product_rating_files');
    }
}
