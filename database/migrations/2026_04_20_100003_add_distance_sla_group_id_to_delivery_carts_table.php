<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistanceSlaGroupIdToDeliveryCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_carts', function (Blueprint $table) {
            $table->unsignedBigInteger('distance_sla_group_id')->nullable()->after('estimated_time');
            $table->foreign('distance_sla_group_id')
                ->references('id')
                ->on('distance_sla_groups')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_carts', function (Blueprint $table) {
            $table->dropForeign(['distance_sla_group_id']);
            $table->dropColumn('distance_sla_group_id');
        });
    }
}
