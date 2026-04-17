<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToSubscriptionInvoicesVendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_invoices_vendor', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('vendor_id');
            $table->unsignedBigInteger('approved_by')->nullable()->after('cancelled_at');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_by');
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
            $table->dropColumn('user_id');
            $table->dropColumn('approved_by');
            $table->dropColumn('rejected_by');
        });
    }
}
