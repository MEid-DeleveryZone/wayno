<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SubscriptionValiditiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('subscription_validities')->delete();
 
        $vals = array(
            array(
                'id' => 1,
                'name' => 'Weekly',
                'Description' => '',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 2,
                'name' => 'Monthly',
                'Description' => '',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'id' => 3,
                'name' => 'Yearly',
                'Description' => '',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            )
        ); 
        \DB::table('subscription_validities')->insert($vals);
    }
}
