<?php

namespace Database\Seeders;
use DB;
use Illuminate\Database\Seeder;
use App\Models\DispatcherStatusOption;
use App\Models\VendorOrderDispatcherStatus;

class DispatcherStatusOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['Created', 'Assigned', 'Started', 'Arrived', 'Completed'];
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('dispatcher_status_options')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($statuses as $status) {
	        DispatcherStatusOption::create(['title' => $status, 'status' => 1, 'type' => 1]);
        }
    }
}
