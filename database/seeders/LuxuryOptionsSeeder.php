<?php

namespace Database\Seeders;

use App\Models\LuxuryOption;
use Illuminate\Database\Seeder;

class LuxuryOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LuxuryOption::truncate();
        $luxuryOption = LuxuryOption::insert([
            'title' => 'delivery',
        ]);
        $luxuryOption = LuxuryOption::insert([
            'title' => 'dine_in',
        ]);
        $luxuryOption = LuxuryOption::insert([
            'title' => 'takeaway',
        ]);
    }
}
