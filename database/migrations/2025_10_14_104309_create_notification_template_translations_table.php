<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTemplateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_template_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('notification_template_id')->unsigned();
            $table->bigInteger('language_id')->unsigned();
            $table->mediumText('subject')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });
        
        Schema::table('notification_template_translations', function (Blueprint $table) {
            $table->foreign('notification_template_id', 'nt_translations_template_id_foreign')
                ->references('id')->on('notification_templates')->onDelete('cascade');
            $table->foreign('language_id', 'nt_translations_language_id_foreign')
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
        Schema::dropIfExists('notification_template_translations');
    }
}
