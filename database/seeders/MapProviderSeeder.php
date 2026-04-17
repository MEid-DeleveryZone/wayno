<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class MapProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('map_providers')->delete();
 
        $maps = array(
            array(
                'id' => 1,
                'provider' => 'Google Map',
                'keyword' => 'google_map',
                'status' => '1'
            ),
            array(
                'id' => 2,
                'provider' => 'Map Box',
                'keyword' => 'map_box',
                'status' => '0'
            ),
        ); 
        \DB::table('map_providers')->insert($maps);
    }
}
