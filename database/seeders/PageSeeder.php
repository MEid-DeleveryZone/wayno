<?php

namespace Database\Seeders;
use DB;
use App\Models\Page;
use Illuminate\Support\Str;
use App\Models\ClientLanguage;
use Illuminate\Database\Seeder;
use App\Models\PageTranslation;

class PageSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pages')->truncate();
        DB::table('page_translations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $page_array = ['Privacy Policy', 'Terms & Conditions', 'Vendor Registration'];
        $client_language = ClientLanguage::where('is_primary', 1)->first();
        foreach ($page_array as $page) {
            $page_detail = Page::create(['slug' => Str::slug($page, '-')]);
            PageTranslation::create([
                'title' => $page, 
                'is_published' => 1, 
                'page_id' => $page_detail->id, 
                'language_id' => $client_language ? $client_language->language_id : 0, 
                'description' => 'We provide Visitors (as defined below) with access to the Website and Registered Members (as defined below) with access to the Platform subject to the following Terms of Use. By browsing the public areas of the Website, you acknowledge that you have read, understood, and agree to be legally bound by these Terms of Use and our Privacy Policy, which is hereby incorporated by reference (collectively, this “Agreement”). If you do not agree to any of these terms, then please do not use the Website, the App, and/or the Platform. We may change the terms and conditions of these Terms of Use from time to time with or without notice to you.', 
            ]);
        }
    }
}
