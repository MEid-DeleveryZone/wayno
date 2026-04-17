<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_rate_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tax_cate_id')->unsigned();
            $table->bigInteger('tax_rate_id')->unsigned();
            $table->timestamps();

            $table->foreign('tax_cate_id')->references('id')->on('tax_categories')->onDelete('cascade');
            $table->foreign('tax_rate_id')->references('id')->on('tax_rates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_rate_categories');
    }
}
