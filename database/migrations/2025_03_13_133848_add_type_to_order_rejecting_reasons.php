<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToOrderRejectingReasons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_rejecting_reasons', function (Blueprint $table) {
            $table->tinyInteger('type')->after('name')->default(0)->comment('0-admin, 1-customer');
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
            $table->dropColumn('type');
        });
    }
}