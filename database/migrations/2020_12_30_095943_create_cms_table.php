<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('sorting')->default(1);
            $table->string('title', 60)->unique();
            $table->longText('html_content')->nullable();
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            //$table->string('client_code', 10)->nullable();
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 - active, 0 - pending, 2 - blocked');
            $table->timestamps();
        });

        Schema::table('cms', function (Blueprint $table) {
            //$table->foreign('client_code')->references('id')->on('clients')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('language_id')->references('language_id')->on('client_languages')->onDelete('cascade');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms');
    }
}
