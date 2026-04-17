<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimezoneFieldUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'timezone')) {
                $table->dropColumn('timezone');
            }
            $table->bigInteger('timezone_id')->unsigned()->nullable();
            $table->foreign('timezone_id')->references('id')->on('timezones')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['timezone_id']);
            $table->dropColumn('timezone_id');
        });
    }
}
