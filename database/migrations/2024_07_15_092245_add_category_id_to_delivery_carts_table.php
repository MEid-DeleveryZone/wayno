<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToDeliveryCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_carts', function (Blueprint $table) {
            $table->bigInteger('category_id')->after('product_id');
            $table->longText('client_comment')->after('is_same_emirate')->nullable();
            $table->string('vehicle_number')->after('client_comment')->nullable();
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
            $table->dropColumn('category_id');
            $table->dropColumn('client_comment');
            $table->dropColumn('vehicle_number');
        });
    }
}
