<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->tinyInteger('status')->comment('0-Created, 1-Confirmed, 2-Dispatched, 3-Delivered');
            $table->tinyInteger('is_deleted')->comment('0-No, 1-Yes');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_email')->nullable();
            $table->string('recipient_number')->nullable();
            $table->tinyInteger('paid_via_wallet')->comment('0-No, 1-Yes');
            $table->tinyInteger('paid_via_loyalty')->comment('0-No, 1-Yes');
            $table->decimal('total_amount')->unsigned()->nullable();
            $table->decimal('total_discount')->unsigned()->nullable();
            $table->decimal('taxable_amount')->unsigned()->nullable();
            $table->decimal('payable_amount')->unsigned()->nullable();
            $table->unsignedBigInteger('tax_category_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('user_addresses')->onDelete('set null');
            $table->foreign('tax_category_id')->references('id')->on('tax_categories')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
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
        Schema::dropIfExists('orders');
    }
}
