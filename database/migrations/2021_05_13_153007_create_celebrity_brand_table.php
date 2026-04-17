<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCelebrityBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('celebrity_brands', function (Blueprint $table) {
            $table->unsignedBigInteger('celebrity_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();

            $table->foreign('celebrity_id')->references('id')->on('celebrities')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('celebrity_product');
    }
}
