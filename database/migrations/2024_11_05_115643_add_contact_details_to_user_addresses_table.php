<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactDetailsToUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('name')->nullable()->after('building_villa_flat_no');
            $table->string('phone_number_type')->nullable()->after('name');
            $table->string('phone_number')->nullable()->after('phone_number_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('phone_number_type');
            $table->dropColumn('phone_number');
        });
    }
}
