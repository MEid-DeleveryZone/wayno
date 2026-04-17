<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Promocode;
use App\Models\PromocodeTranslation;
use App\Models\ClientLanguage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MigrateExistingPromocodeDataToTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get all promocodes with existing title, short_desc, and image
        $promocodes = DB::table('promocodes')->get();
        
        foreach ($promocodes as $promocode) {
            // Get primary language for the client
            $primaryLanguage = DB::table('client_languages')
                ->where('is_primary', 1)
                ->where('is_active', 1)
                ->first();
            
            if ($primaryLanguage && ($promocode->title || $promocode->short_desc || $promocode->image)) {
                // Insert translation record for primary language
                DB::table('promocode_translations')->insert([
                    'promocode_id' => $promocode->id,
                    'language_id' => $primaryLanguage->language_id,
                    'title' => $promocode->title ?? '',
                    'short_desc' => $promocode->short_desc ?? '',
                    'image' => $promocode->image ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Delete all promocode translations
        DB::table('promocode_translations')->truncate();
    }
}
