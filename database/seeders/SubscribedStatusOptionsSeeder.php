<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SubscribedStatusOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('subscribed_status_options')->delete();
 
        $statuses = array(
            array(
                'id' => 1,
                'title' => 'Pending',
                'type' => 1,
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 2,
                'title' => 'Active',
                'type' => 1,
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 3,
                'title' => 'Ended',
                'type' => 1,
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 4,
                'title' => 'Cancelled',
                'type' => 1,
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
        ); 
        \DB::table('subscribed_status_options')->insert($statuses);
    }
}
