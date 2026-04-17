<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstimatedTimeToDeliveryCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_carts', function (Blueprint $table) {
            $table->datetime('estimated_time')->nullable()->after('schedule_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_carts', function (Blueprint $table) {
            $table->dropColumn('estimated_time');
        });
    }
}
