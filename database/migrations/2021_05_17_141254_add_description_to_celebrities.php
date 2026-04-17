<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToCelebrities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('celebrities', 'address')){
            Schema::table('celebrities', function (Blueprint $table) {
             $table->dropColumn('address');
            });
        }
        Schema::table('celebrities', function (Blueprint $table) {
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('celebrities', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
