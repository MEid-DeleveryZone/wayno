<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBrandCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('brand_categories', 'id')) {

            Schema::table('brand_categories', function (Blueprint $table) {
                $table->bigIncrements('id')->change();
            });

        }
        else{
            Schema::table('brand_categories', function (Blueprint $table) {
                $table->bigIncrements('id')->first();
            });
        }   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        if (Schema::hasColumn('brand_categories', 'id')) {
            Schema::table('brand_categories', function (Blueprint $table) {
                //
                $table->dropColumn('id');
            });
        }
    }
}
