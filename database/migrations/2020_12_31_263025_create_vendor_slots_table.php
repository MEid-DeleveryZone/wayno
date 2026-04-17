<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_slots', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->bigInteger('geo_id')->unsigned()->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->tinyInteger('dine_in')->default(0)->comment('1 for yes, 0 for no');
            $table->tinyInteger('takeaway')->default(0)->comment('1 for yes, 0 for no');
            $table->tinyInteger('delivery')->default(0)->comment('1 for yes, 0 for no');
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->index('start_time');
            $table->index('end_time');
            $table->index('dine_in');
            $table->index('takeaway');
            $table->index('delivery');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_slots');
    }
}


