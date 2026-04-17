<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_rejecting_reason_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_rejecting_reason_id');
            $table->unsignedBigInteger('language_id');
            $table->string('reason');
            $table->timestamps();

            $table->unique(['order_rejecting_reason_id', 'language_id'], 'orr_reason_language_unique');
            $table->foreign('order_rejecting_reason_id', 'orr_reason_fk')
                ->references('id')
                ->on('order_rejecting_reasons')
                ->onDelete('cascade');
            $table->foreign('language_id', 'orr_language_fk')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_rejecting_reason_translations');
    }
};

