<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppStylingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_styling_options', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('app_styling_id')->unsigned()->nullable();
            $table->foreign('app_styling_id')->references('id')->on('app_stylings')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('is_selected')->comment('1-yes, 2-no')->default('1');
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
        Schema::dropIfExists('app_styling_options');
    }
}
