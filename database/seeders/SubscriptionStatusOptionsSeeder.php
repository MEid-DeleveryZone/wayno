<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SubscriptionStatusOptionsSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        \DB::table('subscription_status_options')->delete();
 
        $statuses = array(
            array(
                'id' => 1,
                'title' => 'Pending',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 2,
                'title' => 'Active',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 3,
                'title' => 'Inactive',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 4,
                'title' => 'Rejected',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 5,
                'title' => 'Cancelled',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
        ); 
        \DB::table('subscription_status_options')->insert($statuses);
    }
}
