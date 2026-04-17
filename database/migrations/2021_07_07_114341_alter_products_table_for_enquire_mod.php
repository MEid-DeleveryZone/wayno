<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsTableForEnquireMod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('inquiry_only')->nullable()->after('averageRating')->default(0);
        });
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->tinyInteger('enquire_mode')->nullable()->after('id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
