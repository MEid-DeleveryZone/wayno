<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\DispatcherTemplateTypeOption;

class DispatcherTemplateTypeOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $array = array('Pickup & Delivery' => 'template1.png', 'Cab Booking' => 'template2.png');
        DispatcherTemplateTypeOption::truncate();
        foreach ($array as $key => $val) {
            DispatcherTemplateTypeOption::create(['title' => $key, 'image_path' => $val, 'status' => 1]);
        }
    }
}
