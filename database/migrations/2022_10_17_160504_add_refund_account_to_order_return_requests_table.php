<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundAccountToOrderReturnRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_return_requests', function (Blueprint $table) {
            $table->tinyInteger('refund_account')->default(1)->comment('1-Wallet, 2-Card');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_return_requests', function (Blueprint $table) {
            //
        });
    }
}
