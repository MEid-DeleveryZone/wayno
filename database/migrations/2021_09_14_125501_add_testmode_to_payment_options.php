<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTestmodeToPaymentOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_options', function (Blueprint $table) {
            $table->unsignedTinyInteger('test_mode')->default(0)->comment('0 = false, 1 = true');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_options', function (Blueprint $table) {
            $table->dropColumn('test_mode');
        });
    }
}
