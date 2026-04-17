<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variant_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->nullable();
            $table->bigInteger('variant_id')->unsigned()->nullable();
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('variant_id')->references('id')->on('variants')->onDelete('cascade');
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
        Schema::dropIfExists('variant_translations');
    }
}

