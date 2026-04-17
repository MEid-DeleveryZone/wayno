<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CabBookingLayout;
use DB;
use Log;
class HomePageLabelSeederRearrange extends Seeder
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
        $alredy_one = CabBookingLayout::where('order_by', 1)->first();
        $vendor_one = CabBookingLayout::where('slug', 'vendors')->first();
        $update_vendor = CabBookingLayout::where('slug', 'vendors')->update(['order_by' => 1]);
        $update_vendor = CabBookingLayout::where('id', $alredy_one->id??0)->update(['order_by' => $vendor_one->order_by??4]);
        $already = CabBookingLayout::where('slug', 'brands')->update(['order_by' => 6]);

       
    }
}
