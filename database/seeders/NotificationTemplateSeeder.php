<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;
use App\Models\NotificationTemplateTranslation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $create_array = [
            [
                'label' =>'New Order',
                'subject' =>'New Vendor Signup',
                'tags' => '', 
                'content' => 'Thanks for your Order',
                'slug' => 'new-order'
            ],
            [
                'label' => 'Order Status Update',
                'subject' => 'Verify Mail',
                'tags' => '', 
                'content' => 'Your Order status has been updated',
                'slug' => 'order-status-update'
            ],
            [
                'label' =>'Refund Status Update',
                'subject' => 'Reset Password Notification',
                'tags' => '',
                'content' => 'Your Order status has been updated',
                'slug' => 'refund-status-update'
            ],
            [
                'label' =>'New Order Received (Owner)',
                'subject' => 'New Order Received',
                'tags' => '',
                'content' => 'You have received a new order',
                'slug' => 'new-order-received'
            ],
            [
                'label' =>'Order Accepted (Customer)',
                'subject' => 'Order Accepted',
                'tags' => '{order_id}',
                'content' => 'Your order ({order_id}) has been accepted',
                'slug' => 'order-accepted'
            ],
            [
                'label' =>'Order Cancelled (Customer)',
                'subject' => 'Order Cancelled',
                'tags' => '{order_id}',
                'content' => 'Your order ({order_id}) has been cancelled',
                'slug' => 'order-cancelled'
            ],
            [
                'label' =>'Order Processing (Customer)',
                'subject' => 'Order Processed',
                'tags' => '{order_id}',
                'content' => 'Your order ({order_id}) has been processed',
                'slug' => 'order-processing'
            ],
            [
                'label' =>'Out for Pickup (Customer)',
                'subject' => 'Out for Pickup',
                'tags' => '{order_id}',
                'content' => 'Your order ({order_id}) has been reached to you soon',
                'slug' => 'order-out-for-pickup'
            ],
            [
                'label' =>'Order Delivered (Customer)',
                'subject' => 'Order Delivered',
                'tags' => '{order_id}',
                'content' => 'Your order ({order_id}) has delivered',
                'slug' => 'order-delivered'
            ],
            [
                "label" => "Place Order Reminder (Customer)",
                "subject" => "Don't wait too much",
                "tags" => "",
                "content" => "Place your order before it's too late",
                "slug" => "place-order-reminder"
            ],
            [
                "label" => "Order Picked (Customer)",
                "subject" => "Order Picked",
                "tags" => "{order_id}",
                "content" => "Your order ({order_id}) has been picked up and is on the way",
                "slug" => "order-picked"
            ],
            [
                "label" => "Order Out for Delivery (Customer)",
                "subject" => "Out for Delivery",
                "tags" => "{order_id}",
                "content" => "Your order ({order_id}) is out for delivery and will arrive soon",
                "slug" => "order-out-for-delivery"
            ],
            [
                "label" => "Rider Arrived (Customer)",
                "subject" => "Rider Arrived",
                "tags" => "{order_id}",
                "content" => "Your rider has arrived with order ({order_id})",
                "slug" => "rider-arrived"
            ],
            [
                "label" => "Order Cancelled by Admin (Customer)",
                "subject" => "Order Cancelled by Admin",
                "tags" => "{order_id}",
                "content" => "Your order ({order_id}) has been cancelled by our admin team",
                "slug" => "order-cancelled-by-admin"
            ],
            [
                "label" => "Order Cancelled by Dispatcher (Customer)",
                "subject" => "Order Cancelled by Dispatcher",
                "tags" => "{order_id}",
                "content" => "Your order ({order_id}) has been cancelled by our delivery team",
                "slug" => "order-cancelled-by-dispatcher"
            ]
        ];
        
        DB::table('notification_template_translations')->truncate();
        NotificationTemplate::truncate();
        
        foreach ($create_array as $key => $array) {
            // Create notification template without subject and content
            $template = NotificationTemplate::create([
                'label' => $array['label'], 
                'slug' => $array['slug'], 
                'tags' => $array['tags']
            ]);
            
            // Create translation for default language (English - ID: 1)
            NotificationTemplateTranslation::create([
                'notification_template_id' => $template->id,
                'language_id' => 1, // Default English language
                'subject' => $array['subject'],
                'content' => $array['content']
            ]);
        }
    }
}
