<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDineinTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_dinein_tables', function (Blueprint $table) {
            $table->id();
            $table->string("table_number")->nullable();
            $table->string("image")->nullable();
            $table->bigInteger('vendor_dinein_category_id')->unsigned()->nullable();
            $table->foreign('vendor_dinein_category_id')->references('id')->on('vendor_dinein_categories')->onDelete('cascade');;
            $table->tinyInteger('status')->default(0)->comment('0-active, 1-inactive');
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
        Schema::dropIfExists('vendor_dinein_tables');
    }
}
