<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorSlotDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_slot_dates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('specific_date');
            $table->tinyInteger('working_today')->default(1)->comment('1 - yes, 0 - no');
            $table->tinyInteger('dine_in')->default(0)->comment('1 for yes, 0 for no');
            $table->tinyInteger('takeaway')->default(0)->comment('1 for yes, 0 for no');
            $table->tinyInteger('delivery')->default(0)->comment('1 for yes, 0 for no');
            $table->timestamps();

            $table->index('specific_date');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('vendor_slot_dates');
    }
}
