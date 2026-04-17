<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomePageLabelTransaltionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_page_label_transaltions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->bigInteger('home_page_label_id')->unsigned()->nullable();
            $table->foreign('home_page_label_id')->references('id')->on('home_page_labels')->onDelete('cascade');
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
        Schema::dropIfExists('home_page_label_transaltions');
    }
}
