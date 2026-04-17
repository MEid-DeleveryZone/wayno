<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->tinyInteger('is_prohibited_item_enabled')->after('is_image_upload_enabled')->default(1)->comment('1-active, 0-inactive');
            $table->string('vendor_heading')->after('is_prohibited_item_enabled')->nullable();
            $table->string('product_heading')->after('vendor_heading')->nullable();
            $table->integer('max_purchase_amount')->after('product_heading')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('is_prohibited_item_enabled');
            $table->dropColumn('vendor_heading');
            $table->dropColumn('product_heading');
            $table->dropColumn('max_purchase_amount');
        });
    }
}
