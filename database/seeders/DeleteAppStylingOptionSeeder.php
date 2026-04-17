<?php
namespace Database\Seeders;
use DB;
use Illuminate\Database\Seeder;

class DeleteAppStylingOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('app_styling_options')->where('image','home_two.png')->delete();
        DB::table('app_styling_options')->where('image','home_three.png')->delete();
       
    }
}
