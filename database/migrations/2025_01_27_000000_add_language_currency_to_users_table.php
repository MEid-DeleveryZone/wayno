<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageCurrencyToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('language_id')->nullable()->after('country_id')->comment('User preferred language');
            $table->unsignedBigInteger('currency_id')->nullable()->after('language_id')->comment('User preferred currency');
            
            // Optional: Add indexes for better query performance
            $table->index('language_id');
            $table->index('currency_id');
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
            $table->dropIndex(['language_id']);
            $table->dropIndex(['currency_id']);
            $table->dropColumn(['language_id', 'currency_id']);
        });
    }
}

