<?php

namespace Database\Seeders;
use DB;
use App\Models\OrderStatusOption;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_status_options')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $statuses = ['Placed', 'Accepted','Rejected', 'Processing', 'Out For Delivery', 'Delivered', 'Accept', 'Reject'];
        foreach ($statuses as $status) {
            if(in_array($status, ['Accept', 'Reject'])){
    	        OrderStatusOption::create(['title' => $status, 'status' => 1, 'type' => 2]);
            }else{
                OrderStatusOption::create(['title' => $status, 'status' => 1, 'type' => 1]);
            }
        }
    }
}
