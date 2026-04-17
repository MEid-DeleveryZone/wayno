<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCartCouponsForVendorIdsFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('promocodes', 'vendor_id')){
            Schema::table('promocodes', function (Blueprint $table) {
             $table->dropColumn('vendor_id');
            });
        }
        Schema::table('cart_coupons', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
      Schema::table('cart_coupons', function (Blueprint $table) {
        $table->dropColumn('vendor_id');
      });
    }
}
