<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuildingVillaFlatNoToDeliveryCartTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_cart_tasks', function (Blueprint $table) {
            $table->string('building_villa_flat_no')->after('longitude')->nullable();
            $table->string('street')->after('building_villa_flat_no')->nullable();
            $table->string('city')->after('street')->nullable();
            $table->string('area')->after('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_cart_tasks', function (Blueprint $table) {
            $table->dropColumn('building_villa_flat_no');
            $table->dropColumn('street');
            $table->dropColumn('city');
            $table->dropColumn('area');
        });
    }
}
