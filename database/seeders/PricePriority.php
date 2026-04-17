<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PricePriority extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('price_prioritys')->delete();
 
        $type = array(
            array(
                'id' => 1,
                'first'  => 'Driver Tag',
                'second' => 'Team Tag',
                'third'  => 'Geo Fence',
                'fourth' => 'Team Id',

            )
        );
        \DB::table('price_prioritys')->insert($type);
    }
    
}
