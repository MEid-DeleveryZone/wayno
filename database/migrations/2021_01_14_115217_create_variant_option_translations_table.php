<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantOptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variant_option_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->nullable();
            $table->bigInteger('variant_option_id')->unsigned()->nullable();
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('variant_option_id')->references('id')->on('variant_options')->onDelete('cascade');
            $table->foreign('language_id')->references('language_id')->on('client_languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variant_option_translations');
    }
}