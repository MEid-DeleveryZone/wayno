<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('templates')->delete();
 
        $maps = array(
            array(
                'id' => 1,
                'name' => 'Default',
                'image' => 'default/templete.jpg',
                'for' => '1'
            ),
            array(
                'id' => 2,
                'name' => 'Default',
                'image' => 'default/templete.jpg',
                'for' => '2'
            ),
        ); 
        \DB::table('templates')->insert($maps);


        /*      vendors         */
        \DB::table('vendors')->delete();
 
        $vendor = array(
            array(
                'id'=> 1,
                'name' => 'DeliveryZone',
                'desc' => NULL,
                'logo' => 'default/default_logo.png',
                'banner' => 'default/default_image.png',
                'address' => 'Sheikh Zayed Road - Dubai - United Arab Emirates',
                'latitude' => '25.060924600000',
                'longitude' => '55.128979500000',
                'order_min_amount' => '0.00',
                'order_pre_time' => NULL,
                'auto_reject_time' => NULL,
                'commission_percent' => 1,
                'commission_fixed_per_order' => '0.00',
                'commission_monthly' => '0.00',
                'dine_in' => 0,
                'takeaway' => 1,
                'delivery' => 1,
                'status' => 1,
                'add_category' => 1,
                'setting' => 0,
            ),
           
        ); 
        \DB::table('vendors')->insert($vendor);
    }
}