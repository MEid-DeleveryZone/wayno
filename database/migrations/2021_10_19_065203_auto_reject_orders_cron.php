<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AutoRejectOrdersCron extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_reject_orders_cron', function (Blueprint $table) {
            $table->id();
            $table->string('database_host')->nullable();
            $table->string('database_name', 50)->nullable();
			$table->string('database_username', 50)->nullable();
			$table->string('database_password', 50)->nullable();
            $table->integer('order_vendor_id')->nullable();
            $table->dateTime('auto_reject_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auto_reject_orders_cron');
    }
}
