<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('email_token', 20)->nullable();
            $table->timestamp('email_token_valid_till')->nullable();
            $table->string('phone_token', 20)->nullable();
            $table->timestamp('phone_token_valid_till')->nullable();
            $table->tinyInteger('is_email_verified')->default(0)->comment('1 for yes, 0 for no');
            $table->tinyInteger('is_phone_verified')->default(0)->comment('1 for yes, 0 for no');
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
            $table->dropColumn('image');
            $table->dropColumn('email_token');
            $table->dropColumn('email_token_valid_till');
            $table->dropColumn('phone_token');
            $table->dropColumn('phone_token_valid_till');
            $table->dropColumn('is_email_verified');
            $table->dropColumn('is_phone_verified');
        });
    }
}
