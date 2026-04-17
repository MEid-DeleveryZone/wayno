<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCredentialsToPaymentOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_options', function (Blueprint $table) {
            $table->json('credentials')->nullable()->after('title')->comment('credentials in json format');
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
            $table->dropColumn('credentials');
        });
    }
}
