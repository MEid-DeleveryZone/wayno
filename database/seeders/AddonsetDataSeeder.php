<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class AddonsetDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    //private 1 = 1;

    public function __construct()
    {
        //$this->langId = 1;
    }
    public function run(){

        /* Add on sets*/
        \DB::table('addon_sets')->delete();
 
        $addons = array(
            array(
                'id' => '1',
                'title' => 'Small Parcels',
                'min_select' => 1,
                'max_select' => 1,
                'position' => 1,
                'status' => 1,
                'is_core' => 1,
            ),
        ); 
        \DB::table('addon_sets')->insert($addons);

        /* Add on sets tranlation*/
        \DB::table('addon_set_translations')->delete();
 
        $set_translation = array(
            array(
                'id' => '1',
                'title' => 'Small Parcels',
                'addon_id' => 1,
                'language_id' => 1
            ),
        ); 
        \DB::table('addon_set_translations')->insert($set_translation);


        /* Add on options*/
        \DB::table('addon_options')->delete();
 
        $option = array(
            array(
                'id' => '1',
                'title' => 'Small parcel',
                'addon_id' => 1,
                'position' => 1,
                'price' => '100.00'
            )
        ); 
        \DB::table('addon_options')->insert($option);

        /* Add on options  translation */
        \DB::table('addon_option_translations')->delete();
 
        $addOptTrans = array(
            array(
                'id' => '1',
                'title' => 'Small parcel',
                'addon_opt_id' => 1,
                'language_id' => 1
            ),
        ); 
        \DB::table('addon_option_translations')->insert($addOptTrans);
    }
}
