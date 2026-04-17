<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialLoginField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('facebook_auth_id')->nullable();
            $table->string('twitter_auth_id')->nullable();
            $table->string('google_auth_id')->nullable();
            $table->string('apple_auth_id')->nullable();

            $table->index('facebook_auth_id');
            $table->index('twitter_auth_id');
            $table->index('google_auth_id');
            $table->index('apple_auth_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('facebook_auth_id');
            $table->dropColumn('twitter_auth_id');
            $table->dropColumn('google_auth_id');
            $table->dropColumn('apple_auth_id');
        });
    }
}