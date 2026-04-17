<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addserviceskeyinconfigtable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->string('need_dispacher_home_other_service')->nullable();
            $table->string('dispacher_home_other_service_key')->nullable();
            $table->string('dispacher_home_other_service_key_url')->nullable();
            $table->string('dispacher_home_other_service_key_code')->nullable();
        });
    } 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->dropColumn('need_dispacher_home_other_service');
            $table->dropColumn('dispacher_home_other_service_key');
            $table->dropColumn('dispacher_home_other_service_key_url');
            $table->dropColumn('dispacher_home_other_service_key_code');
        });
    }
}
