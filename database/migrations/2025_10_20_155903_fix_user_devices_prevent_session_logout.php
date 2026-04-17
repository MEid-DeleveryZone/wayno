<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixUserDevicesPreventSessionLogout extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration fixes the session logout issue by:
     * 1. Cleaning up duplicate device records
     * 2. Adding unique constraint to prevent future duplicates
     *
     * @return void
     */
    public function up()
    {
        // Step 1: Clean up duplicate records - keep only the most recent one for each user+device combination
        // This prevents the situation where multiple users on the same device overwrite each other's sessions
        DB::statement('
            DELETE t1 FROM user_devices t1
            INNER JOIN user_devices t2 
            WHERE t1.id < t2.id 
            AND t1.user_id = t2.user_id 
            AND t1.device_token = t2.device_token
        ');

        // Step 2: Add unique constraint to enforce one record per user+device combination
        // This prevents the code from creating duplicates even if there's a bug
        Schema::table('user_devices', function (Blueprint $table) {
            $table->unique(['user_id', 'device_token'], 'user_device_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_devices', function (Blueprint $table) {
            $table->dropUnique('user_device_unique');
        });
    }
}
