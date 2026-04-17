<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortOrderToOrderStatusOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_status_options', function (Blueprint $table) {
            $table->integer('sort_order')->after('status')->nullable();
            $table->string('description')->after('sort_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_status_options', function (Blueprint $table) {
            $table->dropColumn('sort_order');
            $table->dropColumn('description');
        });
    }
}
