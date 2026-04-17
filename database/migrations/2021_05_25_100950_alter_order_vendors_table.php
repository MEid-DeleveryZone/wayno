<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_vendor_products', function (Blueprint $table) {
            DB::statement("ALTER TABLE `order_vendor_products` CHANGE `price` `price` decimal(8,2) NULL AFTER `image`");
        });
        Schema::table('order_vendors', function (Blueprint $table) {
            DB::statement("ALTER TABLE `order_vendors`CHANGE `coupon_id` `coupon_id` bigint(20) unsigned NULL AFTER `status`, CHANGE `created_at` `created_at` timestamp NULL AFTER `coupon_id`, CHANGE `updated_at` `updated_at` timestamp NULL AFTER `created_at`");
            $table->decimal('payable_amount', $precision = 8, $scale = 2)->after('coupon_id')->nullable();;
            $table->decimal('discount_amount', $precision = 8, $scale = 2)->after('payable_amount')->nullable();;
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
