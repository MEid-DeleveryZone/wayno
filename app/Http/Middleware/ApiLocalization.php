<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use App\Models\{ClientLanguage, Language};

class ApiLocalization
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request, Closure $next)
    {
        // Check header request and determine localization
        $langValue = ($request->hasHeader('language')) ? $request->header('language') : 1;
        $localeCode = 'en'; // Default locale

        // Check if the value is numeric (old way: language_id) or string (new way: language code like 'en', 'ae', 'ar')
        if(is_numeric($langValue)){
            // Old way: numeric language_id
            // Try to get from config first (for backward compatibility)
            if (array_key_exists($langValue, $this->app->config->get('app.supported_languages'))) {
                $localeCode = $this->app->config->get('app.supported_languages.'.$langValue);
            } else {
                // Fallback: get from database
                $checkLang = ClientLanguage::with('language:id,sort_code')
                    ->where('language_id', $langValue)
                    ->first();
                if($checkLang && $checkLang->language){
                    $localeCode = $checkLang->language->sort_code;
                }
            }
        } else {
            // New way: language code like 'en', 'ar', 'ae'
            // First, check if it's a valid language code in database
            $checkLang = ClientLanguage::whereHas('language', function($q) use ($langValue) {
                $q->where('sort_code', $langValue);
            })->with('language:id,sort_code')->first();
            
            if($checkLang && $checkLang->language){
                $localeCode = $checkLang->language->sort_code;
            } else {
                // If not found in database, check if it's a valid locale code directly
                $validLocales = ['en', 'ar', 'fr', 'de', 'es', 'sv', 'ae'];
                if(in_array($langValue, $validLocales)){
                    $localeCode = $langValue;
                }
            }
        }

        // Set laravel localization
        app()->setLocale($localeCode);

        return $next($request);
    }
}
