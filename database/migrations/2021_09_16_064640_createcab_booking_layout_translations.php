<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatecabBookingLayoutTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cab_booking_layout_transaltions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->bigInteger('cab_booking_layout_id')->unsigned()->nullable();
            $table->foreign('cab_booking_layout_id')->references('id')->on('cab_booking_layouts')->onDelete('cascade');
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
        Schema::dropIfExists('cab_booking_layout_transaltions');
    }
}
