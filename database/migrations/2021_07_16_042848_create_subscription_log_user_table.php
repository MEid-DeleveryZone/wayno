<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionLogUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_log_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subscription_invoice_id')->nullable();
            $table->unsignedBigInteger('subscription_id');
            $table->unsignedTinyInteger('payment_option_id')->nullable();
            $table->unsignedTinyInteger('status_id')->default(0);
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->text('transaction_reference')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('next_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->decimal('subscription_amount', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->decimal('paid_amount', 12, 2)->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->dateTime('ended_at')->nullable();
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
        Schema::dropIfExists('subscription_log_user');
    }
}
