<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoyaltyCheckFieldToLoyaltyCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loyalty_cards', function (Blueprint $table) {
            $table->enum('loyalty_check', ['0', '1'])->comment('0-Active, 1-Deactive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loyalty_cards', function (Blueprint $table) {
            $table->dropColumn('loyalty_check');
        });
    }
}
