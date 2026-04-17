<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlaToOrderVendorProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_vendor_products', function (Blueprint $table) {
            $table->string('sla')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('weight')->nullable();
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
            $table->dropColumn('sla');
            $table->dropColumn('dimensions');
            $table->dropColumn('weight');
        });
    }
}
