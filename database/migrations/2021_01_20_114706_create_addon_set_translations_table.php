<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonSetTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addon_set_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable();
            $table->bigInteger('addon_id')->unsigned()->nullable();
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('addon_id')->references('id')->on('addon_sets')->onDelete('cascade');
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
        Schema::dropIfExists('addon_set_translations');
    }
}
