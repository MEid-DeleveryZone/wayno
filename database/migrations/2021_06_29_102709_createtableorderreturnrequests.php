<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createtableorderreturnrequests extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_return_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_vendor_product_id')->unsigned();
            $table->foreign('order_vendor_product_id')->references('id')->on('order_vendor_products')->onDelete('cascade');
            $table->bigInteger('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->bigInteger('return_by')->unsigned();
            $table->string('reason', 220)->nullable();
            $table->text('coments')->nullable();
            $table->enum('status',['Pending','Accepted','Rejected','On-Hold'])->default('Pending');
            $table->foreign('return_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('order_return_requests');
    }
}
