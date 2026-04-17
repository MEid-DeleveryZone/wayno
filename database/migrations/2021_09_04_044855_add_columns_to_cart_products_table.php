<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCartProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_products', function (Blueprint $table) {
            //
            $table->string('schedule_type',100)->nullable()->after('tax_rate_id');
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
        Schema::table('cart_products', function (Blueprint $table) {
            //
            $table->dropColumn('schedule_type');
            $table->dropColumn('scheduled_date_time');
        });
    }
}
