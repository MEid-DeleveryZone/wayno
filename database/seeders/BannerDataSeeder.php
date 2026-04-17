<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class BannerDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Banner seeder*/
        \DB::table('banners')->delete();
 
        $banners = array(
            array(
                'name' => 'Grocery',
                'description' => NULL,
                'image' => 'banner/Z2YypN3MHqBjUd5r3Mwb7o0sDhCmaiKM3PapkXwq.jpg',
                'validity_on' => '0',
                'sorting' => '1',
                'status' => '1',
                'start_date_time' => NULL,
                'end_date_time' => NULL,
                'redirect_category_id' => NULL,
                'redirect_vendor_id' => NULL,
                'link' => NULL,
            ),
            array(
                'name' => 'Ecommerce',
                'description' => NULL,
                'image' => 'default/default_image.png',
                'validity_on' => '0',
                'sorting' => '2',
                'status' => '1',
                'start_date_time' => NULL,
                'end_date_time' => NULL,
                'redirect_category_id' => NULL,
                'redirect_vendor_id' => NULL,
                'link' => NULL,
            ),
            array(
                'name' => 'Pharmacy',
                'description' => NULL,
                'image' => 'default/default_image.png',
                'validity_on' => '0',
                'sorting' => '3',
                'status' => '1',
                'start_date_time' => NULL,
                'end_date_time' => NULL,
                'redirect_category_id' => NULL,
                'redirect_vendor_id' => NULL,
                'link' => NULL,
            ),
        ); 
        \DB::table('banners')->insert($banners);
    }
}
