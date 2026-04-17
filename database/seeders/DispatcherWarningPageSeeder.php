<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\DispatcherWarningPage;
use Illuminate\Support\Arr;

class DispatcherWarningPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array('Design 1' => 'warning2.png', 'Design 2' => 'warning1.png');
        DispatcherWarningPage::truncate();
        foreach ($array as $key => $val) {
            DispatcherWarningPage::create(['title' => $key, 'image_path' => $val, 'status' => 1]);
        }
    }
}
