<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppStyling;
use App\Models\AppStylingOption;

class FontsHomePageStyleAppStylingOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $app_styling = AppStyling::where(['name' => 'Regular Font'])->first();

        if(!empty($app_styling)){
            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id, 'name' => 'Eina02-Regular'])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => 'Eina02-Regular', 'is_selected' => 0]);
            }

            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id, 'name' => 'Metropolis-Regular'])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => 'Metropolis-Regular', 'is_selected' => 0]);
            }
        }

        $app_styling = AppStyling::where(['name' => 'Medium Font'])->first();

        if(!empty($app_styling)){
            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id, 'name' => 'Eina02-SemiBold'])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => 'Eina02-SemiBold', 'is_selected' => 0]);
            }

            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id, 'name' => 'Metropolis-SemiBold'])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => 'Metropolis-SemiBold', 'is_selected' => 0]);
            }
        }

        $app_styling = AppStyling::where(['name' => 'Bold Font'])->first();

        if(!empty($app_styling)){
            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id, 'name' => 'Eina02-Bold'])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => 'Eina02-Bold', 'is_selected' => 0]);
            }

            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id, 'name' => 'Metropolis-Bold'])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => 'Metropolis-Bold', 'is_selected' => 0]);
            }
        }

        $app_styling = AppStyling::where(['name' => 'Tab Bar Style'])->first();

        if(!empty($app_styling)){
            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id, 'image' => 'bar_four.png'])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => 'Tab 4', 'image' => 'bar_four.png', 'is_selected' => 0, 'template_id' => 4]);
            }
            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id, 'image' => 'bar_five.png'])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => 'Tab 5', 'image' => 'bar_five.png', 'is_selected' => 0, 'template_id' => 5]);
            }
        }

        $app_styling = AppStyling::where(['name' => 'Home Page Style'])->first();

        if(!empty($app_styling)){
            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id, 'image' => 'home_four.png'])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => 'Home Page 4', 'image' => 'home_four.png', 'is_selected' => 0, 'template_id' => 2]);
            }
            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id, 'image' => 'home_five.png'])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => 'Home Page 5', 'image' => 'home_five.png', 'is_selected' => 0, 'template_id' => 3]);
            }
        }

        $app_styling = AppStyling::firstOrCreate([
            'name' => 'Home Tag Line',
            'type' => '1'
        ]);

        if(!empty($app_styling)){
            $checkExist = AppStylingOption::where(['app_styling_id' => $app_styling->id])->first();
            if(empty($checkExist)){
                AppStylingOption::create(['app_styling_id' => $app_styling->id, 'name' => __('Create a free account and join us!')]);
            }
        }
    }
}
