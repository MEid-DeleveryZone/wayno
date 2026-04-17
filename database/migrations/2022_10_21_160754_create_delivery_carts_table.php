<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');            
            $table->unsignedBigInteger('vendor_id');            
            $table->unsignedBigInteger('product_id');            
            $table->float('amount', 8, 2)->default(0);
            $table->tinyInteger('recipient_phone')->nullable();
            $table->string('recipient_email')->nullable();
            $table->integer('payment_option_id');            
            $table->tinyInteger('currency_id');
            $table->dateTime('schedule_time');
            $table->enum('status', ['pending', 'paid', 'completed'])->default('pending');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_carts');
    }
}
