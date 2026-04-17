<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Webstylingoptionstable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_styling_options', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('web_styling_id')->unsigned()->nullable();
            $table->foreign('web_styling_id')->references('id')->on('web_stylings')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('is_selected')->comment('1-yes, 2-no')->default('1');
            $table->tinyInteger('template_id')->nullable();
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
        Schema::dropIfExists('web_styling_options');
    }
}
