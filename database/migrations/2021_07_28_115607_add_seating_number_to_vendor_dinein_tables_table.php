<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatingNumberToVendorDineinTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_dinein_tables', function (Blueprint $table) {
            $table->integer('seating_number')->after('vendor_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_dinein_tables', function (Blueprint $table) {
            $table->dropColumn('seating_number');
        });
    }
}
