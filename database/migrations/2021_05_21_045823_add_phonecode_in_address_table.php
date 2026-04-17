<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhonecodeInAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            // if (Schema::hasColumn('user_addresses', 'country_id')) {
            //     $table->dropColumn('country_id');
            // }
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('phonecode')->after('is_primary')->nullable();
            $table->string('country_code')->after('phonecode')->nullable();
            $table->tinyInteger('country')->after('country_code')->nullable();
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
            //
        });
    }
}
