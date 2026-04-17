<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFrequencyToSubscriptionInvoicesVendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_invoices_vendor', function (Blueprint $table) {
            $table->string('frequency')->after('coupon_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_invoices_vendor', function (Blueprint $table) {
            $table->dropColumn('frequency');
        });
    }
}
