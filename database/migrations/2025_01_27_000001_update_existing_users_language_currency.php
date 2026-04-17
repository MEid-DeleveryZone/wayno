<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\{User, ClientLanguage, ClientCurrency};

class UpdateExistingUsersLanguageCurrency extends Migration
{
    /**
     * Run the migrations.
     * This migration updates existing users who don't have language_id and currency_id set.
     *
     * @return void
     */
    public function up()
    {
        // Get primary language and currency
        $primaryLanguage = ClientLanguage::where('is_primary', 1)->first();
        $primaryCurrency = ClientCurrency::where('is_primary', 1)->first();
        
        if ($primaryLanguage && $primaryCurrency) {
            // Update all users who don't have language_id or currency_id
            User::whereNull('language_id')
                ->orWhereNull('currency_id')
                ->update([
                    'language_id' => $primaryLanguage->language_id,
                    'currency_id' => $primaryCurrency->currency_id,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No need to reverse this data migration
    }
}

