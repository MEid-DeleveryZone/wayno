<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CabBookingLayout;
use DB;
use Log;
class HomePageLabelSeederDefault extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');  

        $already = CabBookingLayout::where('slug', 'vendors')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title' => 'Vendors',
            'slug' => 'vendors',
            'order_by' => 1,
        ]);

        $already = CabBookingLayout::where('slug', 'featured_products')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title' => 'Featured Products',
            'slug' => 'featured_products',
            'order_by' => 2,
        ]);

       

        $already = CabBookingLayout::where('slug', 'new_products')->count();

        if($already == 0){
            Log::info($already);
            $home_page = CabBookingLayout::insertGetId([
                'title' => 'New Products',
                'slug' => 'new_products',
                'order_by' => 3,
            ]);
        }
      

        $already = CabBookingLayout::where('slug', 'on_sale')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title' => 'On Sale',
            'slug' => 'on_sale',
            'order_by' => 4,
        ]);

       

        $already = CabBookingLayout::where('slug', 'best_sellers')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title' => 'Best Sellers',
            'slug' => 'best_sellers',
            'order_by' => 5,
        ]);

        $already = CabBookingLayout::where('slug', 'brands')->count();

        if($already == 0)
        $home_page = CabBookingLayout::insertGetId([
            'title' => 'Brands',
            'slug' => 'brands',
            'order_by' => 6,
        ]);
           
       
    }
}
