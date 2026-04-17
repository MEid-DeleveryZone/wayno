<?php

namespace Database\Seeders;
use App\Models\AppStyling;
use App\Models\AppStylingOption;

use Illuminate\Database\Seeder;

class AdditionalHomePageStyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_styling = AppStyling::where(['name' => 'Home Page Style'])->first();

        if(!empty($app_styling)){
            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id, 'image' => 'home_six.png'])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => 'Home Page 6', 'image' => 'home_six.png', 'is_selected' => 0, 'template_id' => 4]);
            }
        }
    }
}
