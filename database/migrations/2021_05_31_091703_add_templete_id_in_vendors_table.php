<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTempleteIdInVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'is_show_category')) {
                $table->dropColumn('is_show_category');
            }

            $table->bigInteger('vendor_templete_id')->unsigned()->nullable();
            $table->foreign('vendor_templete_id')->references('id')->on('vendor_templetes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropForeign(['vendor_templete_id']);
            $table->dropColumn('vendor_templete_id');
        });
    }
}
