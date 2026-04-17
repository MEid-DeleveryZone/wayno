<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToVendorSavedPaymentMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_saved_payment_methods', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_saved_payment_methods', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
