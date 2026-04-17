<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderProductsForRenameTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('order_vendors', function (Blueprint $table) {
            $table->renameColumn('dipatcher_fee', 'delivery_fee');
            $table->bigInteger('coupon_id')->unsigned()->nullable();
        });
        Schema::table('order_products', function (Blueprint $table) {
            DB::statement('ALTER TABLE `order_products` RENAME TO `order_vendor_products`');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
        });
    }
}
