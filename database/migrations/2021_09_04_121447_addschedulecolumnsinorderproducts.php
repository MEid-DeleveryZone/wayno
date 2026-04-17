<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addschedulecolumnsinorderproducts extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_vendor_products', function (Blueprint $table) {
            //
            $table->string('schedule_type',100)->nullable()->after('product_dispatcher_tag');
            $table->dateTime('scheduled_date_time')->nullable()->after('schedule_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_vendor_products', function (Blueprint $table) {
            //
            $table->dropColumn('schedule_type');
            $table->dropColumn('scheduled_date_time');
        });
    }
}
