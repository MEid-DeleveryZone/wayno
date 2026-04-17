<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SubscriptionFeaturesListVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('subscription_features_list_vendor')->delete();
 
        $features = array(
            array(
                'id' => 1,
                'title' => 'Trending',
                'Description' => '',
                'status' => 1,
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            )
        ); 
        \DB::table('subscription_features_list_vendor')->insert($features);
    }
}
