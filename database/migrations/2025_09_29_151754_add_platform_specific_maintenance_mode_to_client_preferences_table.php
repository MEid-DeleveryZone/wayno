<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlatformSpecificMaintenanceModeToClientPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_preferences', function (Blueprint $table) {
            $table->tinyInteger('android_maintenance_mode')->nullable()->default(0)->comment('0-No, 1-Yes')->after('maintenance_mode');
            $table->tinyInteger('ios_maintenance_mode')->nullable()->default(0)->comment('0-No, 1-Yes')->after('android_maintenance_mode');
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
            $table->dropColumn(['android_maintenance_mode', 'ios_maintenance_mode']);
        });
    }
}