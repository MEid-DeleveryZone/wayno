<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderVendorProductsForOrderVendorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_vendors', function (Blueprint $table) {
            $table->tinyInteger('order_status_option_id')->unsigned()->nullable()->after('payment_option_id');
        });
        Schema::table('order_vendor_products', function (Blueprint $table) {
            $table->bigInteger('order_vendor_id')->unsigned()->nullable()->after('product_id');
        });
        Schema::table('vendor_order_statuses', function (Blueprint $table) {
            $table->bigInteger('order_vendor_id')->unsigned()->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
