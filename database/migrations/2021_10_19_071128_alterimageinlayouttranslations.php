<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Alterimageinlayouttranslations extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cab_booking_layouts', function (Blueprint $table) {
            $table->string('image')->nullable()->after('order_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cab_booking_layouts', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}
