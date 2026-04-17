<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->integer('minimum_points')->nullable();
            $table->integer('per_order_minimum_amount')->nullable();
            $table->integer('per_order_points')->nullable();
            $table->integer('per_purchase_minimum_amount')->nullable();
            $table->integer('amount_per_loyalty_point')->nullable();
            $table->integer('redeem_points_per_primary_currency')->nullable();
            $table->enum('status', ['0', '1', '2'])->comment('0-Active, 1-Deactive, 2-Deleted');
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
        Schema::dropIfExists('loyalty_cards');
    }
}
