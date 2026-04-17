<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addreasonbyvendorinreturnrequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_return_requests', function (Blueprint $table) {
            $table->mediumText('reason_by_vendor')->after('coments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_return_requests', function (Blueprint $table) {
            $table->dropColumn('reason_by_vendor');
        });
    }
}
