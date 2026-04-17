<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribed_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('subscriber_type')->nullable();
            $table->unsignedBigInteger('subscription_id');
            $table->mediumText('slug')->nullable();
            $table->unsignedTinyInteger('payment_method')->nullable();
            $table->unsignedTinyInteger('status_id')->default(0);
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->longText('transaction_reference')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('next_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->decimal('subscription_amount', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->timestamps();

            $table->index('status_id');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribed_users');
    }
}
