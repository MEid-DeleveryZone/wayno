<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
                $table->tinyInteger('payment_status')->default(1)->comment('1 - Pending, 2 - Paid, 3 - Failed');
                $table->tinyInteger('payment_method')->default(1)->comment('1 - Credit Card, 2 - Cash On Delivery, 3 - Paypal, 4 - Wallet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_status');
        });
    }
}
