<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocode_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promocode_id')->unsigned();
            $table->bigInteger('language_id')->unsigned();
            $table->string('title')->nullable();
            $table->mediumText('short_desc')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
        
        Schema::table('promocode_translations', function (Blueprint $table) {
            $table->foreign('promocode_id', 'promocode_translations_promocode_id_foreign')
                ->references('id')->on('promocodes')->onDelete('cascade');
            $table->foreign('language_id', 'promocode_translations_language_id_foreign')
                ->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocode_translations');
    }
}
