<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToOrderRejectingReasons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_rejecting_reasons', function (Blueprint $table) {
            $table->tinyInteger('status')->after('type')->default(0)->comment('1-active, 2-inactive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_rejecting_reasons', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
