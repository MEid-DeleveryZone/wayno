<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PromoTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('promo_types')->delete();
        $maps = array(
            array(
                'id' => 1,
                'title' => 'Percentage Discount',
                'status' => '1'
            ),
            array(
                'id' => 2,
                'title' => 'Fixed Amount',
                'status' => '1'
            ),
        ); 
        \DB::table('promo_types')->insert($maps);
    }
}