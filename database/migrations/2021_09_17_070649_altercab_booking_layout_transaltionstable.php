<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AltercabBookingLayoutTransaltionstable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cab_booking_layout_transaltions', function (Blueprint $table) {
            $table->longText('body_html')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cab_booking_layout_transaltions', function (Blueprint $table) {
            $table->dropColumn('body_html');
        });
    }
}
