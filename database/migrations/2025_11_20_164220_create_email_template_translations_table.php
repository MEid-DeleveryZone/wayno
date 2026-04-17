<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_template_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('email_template_id')->unsigned();
            $table->bigInteger('language_id')->unsigned();
            $table->mediumText('subject')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });
        
        Schema::table('email_template_translations', function (Blueprint $table) {
            $table->foreign('email_template_id', 'et_translations_template_id_foreign')
                ->references('id')->on('email_templates')->onDelete('cascade');
            $table->foreign('language_id', 'et_translations_language_id_foreign')
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
        Schema::dropIfExists('email_template_translations');
    }
}
