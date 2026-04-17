<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlaceHolderTxtMsgMandatoryToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->tinyInteger('is_msg_txt_mandatory')->after('is_product_price_show')->default(1)->comment('1-active, 0-inactive');
            $table->string('place_holder_text')->after('is_msg_txt_mandatory')->nullable();
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
            $table->dropColumn('is_msg_txt_mandatory');
            $table->dropColumn('place_holder_text');
        });
    }
}
