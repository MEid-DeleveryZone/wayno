<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppStylingOptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('app_styling_options')->delete();
        
        \DB::table('app_styling_options')->insert(array (
            0 => 
            array (
                'app_styling_id' => 2,
                'name' => 'Roboto',
                'image' => NULL,
                'is_selected' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'app_styling_id' => 2,
                'name' => 'Futura',
                'image' => NULL,
                'is_selected' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'app_styling_id' => 2,
                'name' => 'Helvetica',
                'image' => NULL,
                'is_selected' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'app_styling_id' => 2,
                'name' => 'Raleway',
                'image' => NULL,
                'is_selected' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}