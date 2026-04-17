<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pages');
        Schema::dropIfExists('page_translations');
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->mediumText('slug');
            $table->timestamps();
        });
        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->mediumText('title');
            $table->longText('description');
            $table->bigInteger('page_id')->unsigned();
            $table->bigInteger('language_id')->unsigned();
            $table->mediumText('meta_title')->nullable();
            $table->mediumText('meta_keyword')->nullable();
            $table->mediumText('meta_description')->nullable();
            $table->tinyInteger('is_published')->default(0)->comment('0 draft and 1 for published');
            $table->timestamps();
        });
        Schema::table('page_translations', function (Blueprint $table) {
            $table->foreign('page_id')->references('id')->on('pages');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_translations');
    }
}
