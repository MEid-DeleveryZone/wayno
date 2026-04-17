<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaskTypeToDeliveryCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_carts', function (Blueprint $table) {
            $table->enum('task_type',['now','schedule'])->default('now')->after('schedule_time');
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
            $table->dropColumn('task_type');
        });
    }
}
