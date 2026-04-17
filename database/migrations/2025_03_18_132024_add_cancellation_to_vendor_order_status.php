<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancellationToVendorOrderStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_vendors', function (Blueprint $table) {
            $table->string('comment')->after('reject_reason')->nullable();
            $table->integer('cancelled_by')->after('comment')->default(0)->comments('1-Admin, 2-Customer, 3-Dispatcher, 0-No cancellation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_vendors', function (Blueprint $table) {
            $table->dropColumn('comment');
            $table->dropColumn('cancelled_by');
        });
    }
}