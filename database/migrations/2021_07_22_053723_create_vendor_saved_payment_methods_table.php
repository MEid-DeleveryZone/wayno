<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorSavedPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_saved_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedInteger('payment_option_id')->nullable();
            $table->unsignedInteger('card_last_four_digit')->nullable();
            $table->unsignedInteger('card_expiry_month')->nullable();
            $table->unsignedInteger('card_expiry_year')->nullable();
            $table->text('customerReference')->nullable();
            $table->text('cardReference')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('vendor_id');
            $table->index('payment_option_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_saved_payment_methods');
    }
}
