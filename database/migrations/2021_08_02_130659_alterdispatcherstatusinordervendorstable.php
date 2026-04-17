<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Alterdispatcherstatusinordervendorstable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_vendors', function (Blueprint $table) {
            $table->tinyInteger('dispatcher_status_option_id')->unsigned()->nullable()->after('payment_option_id');
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
            $table->dropColumn('dispatcher_status_option_id');
        });
    }
}
