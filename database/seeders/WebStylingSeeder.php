<?php

namespace Database\Seeders;
use DB;
use App\Models\{WebStyling,WebStylingOption};
use Illuminate\Database\Seeder;

class WebStylingSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('web_stylings')->truncate();    
        DB::table('web_styling_options')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');   
      

        $app_styling = WebStyling::insertGetId([
            'name' => 'Home Page Style',
            'type' => '1'
        ]);

        $app_styling_option = WebStylingOption::insert([
            'web_styling_id' => $app_styling,
            'name' => 'Home Page 1',
            'image' => 'template-one.png',
            'is_selected' => '1',
            'template_id' => '1',
        ]);

        $app_styling_option = WebStylingOption::insert([
            'web_styling_id' => $app_styling,
            'name' => 'Home Page 2',
            'image' => 'template-two.png',
            'is_selected' => '0',
            'template_id' => '2',
        ]);


     
    }
}
