<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCelebritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_celebrities', function (Blueprint $table) {
            $table->unsignedBigInteger('celebrity_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();

            $table->foreign('celebrity_id')->references('id')->on('celebrities')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('product_celebrities');
    }
}
