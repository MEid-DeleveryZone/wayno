<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoyaltyPointsEarnedOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('loyalty_points_used', $precision = 10, $scale = 2)->after('currency_id')->nullable();
            $table->decimal('loyalty_amount_saved', $precision = 10, $scale = 2)->after('loyalty_points_used')->nullable();
            $table->decimal('loyalty_points_earned', $precision = 10, $scale = 2)->after('loyalty_amount_saved')->nullable();
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
