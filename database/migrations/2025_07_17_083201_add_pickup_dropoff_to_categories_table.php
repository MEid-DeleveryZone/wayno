<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPickupDropoffToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->tinyInteger('is_pickup_enabled')->after('is_vendor_register')->default(1)->comment('1-active, 0-inactive');
            $table->tinyInteger('is_dropoff_enabled')->after('is_pickup_enabled')->default(1)->comment('1-active, 0-inactive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('is_pickup_enabled');
            $table->dropColumn('is_dropoff_enabled');
        });
    }
}
