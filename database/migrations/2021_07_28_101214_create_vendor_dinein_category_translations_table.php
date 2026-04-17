<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDineinCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_dinein_category_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('vendor_dinein_categories')->onDelete('cascade');
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');;
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
        Schema::dropIfExists('vendor_dinein_category_translations');
    }
}
