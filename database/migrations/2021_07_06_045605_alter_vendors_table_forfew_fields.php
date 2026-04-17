<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVendorsTableForfewFields extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('email')->nullable()->after('address');
            $table->string('website')->nullable()->after('email');
            $table->string('phone_no')->nullable()->after('website');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('website');
            $table->dropColumn('phone_no');
        });
    }
}
