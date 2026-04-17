<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Model\NotificationType;
use App\Model\NotificationEvent;
class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$notification_types = NotificationType::create([
            'name' => 'Pickup Notifications'
        ]);

        // create all the events in the Pickup Notifications //
        $notification_types->notification_events()->createMany([
            ['name' => 'Request Recieved'],
            ['name' => 'Agent Started'],
            ['name' => 'Agent Arrived'],
            ['name' => 'Successfull'],
            ['name' => 'Failed']
        ]);

        $notification_types_d = NotificationType::create([
            'name' => 'Delivery Notifications'
        ]);

        // create all the events in the Delivery Notifications //
        $notification_types_d->notification_events()->createMany([
            ['name' => 'Request Recieved'],
            ['name' => 'Agent Started'],
            ['name' => 'Agent Arrived'],
            ['name' => 'Successfull'],
            ['name' => 'Failed']
        ]);*/
    }
}
