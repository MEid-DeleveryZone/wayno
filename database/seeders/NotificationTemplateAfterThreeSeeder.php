<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;
use Illuminate\Support\Str;

class NotificationTemplateAfterThreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotificationTemplate::updateOrCreate(['id' => 4],['label' => "New Order Received (Owner)", 'slug' => 'new-order-received', 'subject' => "New Order Received", 'content' => "You have received a new order", 'tags' => ""]);
        NotificationTemplate::updateOrCreate(['id' => 5],['label' => "Order Accepted (Customer)", 'slug' => 'order-accepted', 'subject' => "Order Accepted", 'content' => "Your order ({order_id}) has been accepted", 'tags' => "{order_id}"]);
        NotificationTemplate::updateOrCreate(['id' => 6],['label' => "Order Rejected (Customer)", 'slug' => 'order-rejected', 'subject' => "Order Rejected", 'content' => "Your order ({order_id}) has been rejected", 'tags' => "{order_id}"]);
        NotificationTemplate::updateOrCreate(['id' => 7],['label' => "Order Processing (Customer)", 'slug' => 'order-processing', 'subject' => "Order Processed", 'content' => "Your order ({order_id}) has been processed", 'tags' => "{order_id}"]);
        NotificationTemplate::updateOrCreate(['id' => 8],['label' => "Out of delivery (Customer)", 'slug' => 'order-out-of-delivery', 'subject' => "Out of delivery", 'content' => "Your order ({order_id}) has been reached to you soon", 'tags' => "{order_id}"]);
        NotificationTemplate::updateOrCreate(['id' => 9],['label' => "Order Delivered (Customer)", 'slug' => 'order-delivered', 'subject' => "Order Delivered", 'content' => "Your order ({order_id}) has delivered", 'tags' => "{order_id}"]);
        NotificationTemplate::updateOrCreate(['id' => 10],['label' => "Place Order Reminder (Customer)", 'slug' => 'place-order-reminder', 'subject' => "Don't wait too much", 'content' => "Place your order before it's too late", "tags" => ""]);
    }
}
