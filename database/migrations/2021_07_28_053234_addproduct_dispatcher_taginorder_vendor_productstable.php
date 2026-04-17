<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddproductDispatcherTaginorderVendorProductstable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_vendor_products', function (Blueprint $table) {
            $table->string('product_dispatcher_tag')->nullable();
        });
    } 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_vendor_products', function (Blueprint $table) {
            $table->dropColumn('product_dispatcher_tag');
        });
    }
}
