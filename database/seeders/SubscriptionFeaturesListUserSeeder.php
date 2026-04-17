<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SubscriptionFeaturesListUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('subscription_features_list_user')->delete();
 
        $features = array(
            array(
                'id' => 1,
                'title' => 'Free Delivery',
                'Description' => '',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            )
        ); 
        \DB::table('subscription_features_list_user')->insert($features);
    }
}
