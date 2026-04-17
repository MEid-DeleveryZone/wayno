<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDineinTableTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_dinein_table_translations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->bigInteger('vendor_dinein_table_id')->unsigned()->nullable();
            $table->foreign('vendor_dinein_table_id')->references('id')->on('vendor_dinein_tables')->onDelete('cascade');;
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
        Schema::dropIfExists('vendor_dinein_table_translations');
    }
}
