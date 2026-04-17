<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promocode_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('usage_count')->default(1);

            $table->foreign('promocode_id')->references('id')->on('promocodes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('promo_usages');
    }
}
