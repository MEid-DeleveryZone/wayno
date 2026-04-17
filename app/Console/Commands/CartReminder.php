<?php

namespace App\Console\Commands;

use App\Models\Client;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Config;
use Log;
use App\Models\Cart;
use App\Models\ClientPreference;
use App\Models\UserDevice;
use App\Models\NotificationTemplate;

class CartReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder to all users regarding Cart data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clients = Client::select('database_name', 'sub_domain')->get();
        foreach ($clients as $client) {
            $database_name = 'royo_' . $client->database_name;
            // Log::info("checking cart start: {$database_name}!");
            $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
            $db = DB::select($query, [$database_name]);
            if ($db) {
                $default = [
                    'prefix' => '',
                    'engine' => null,
                    'strict' => false,
                    'charset' => 'utf8mb4',
                    'host' => env('DB_HOST'),
                    'port' => env('DB_PORT'),
                    'prefix_indexes' => true,
                    'database' => $database_name,
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'collation' => 'utf8mb4_unicode_ci',
                    'driver' => env('DB_CONNECTION', 'mysql'),
                ];
                Config::set("database.connections.$database_name", $default);
                DB::setDefaultConnection($database_name);
                $client_preferences = ClientPreference::first();
                $past_4Hours = date('Y-m-d H:i:s', strtotime("-4 hours"));
                $past_5Hours = date('Y-m-d H:i:s', strtotime("-5 hours"));
                $cartList = Cart::join('cart_products', 'cart_products.cart_id', '=', 'carts.id')->where(function ($query) use ($past_4Hours, $past_5Hours) {
                    $query->whereBetween('carts.updated_at', [$past_5Hours, $past_4Hours]);
                })->pluck('user_id')->toArray();
                $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $cartList)->pluck('device_token')->toArray();
                if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
                    $from = $client_preferences->fcm_server_key;
                    $notification_content = NotificationTemplate::where(['id' => 10])->first();
                    if ($notification_content) {
                        $redirect_URL = "https://" . $client->sub_domain . env('SUBMAINDOMAIN') . "/viewcart";
                        $headers = [
                            'Authorization: key=' . $from,
                            'Content-Type: application/json',
                        ];
                        $recipients_array = array_chunk($devices, 1000);
                        foreach ($recipients_array as $recipient_value) {
                            $data = [
                                "registration_ids" => $recipient_value,
                                "notification" => [
                                    'title' => $notification_content->subject,
                                    'body'  => $notification_content->content,
                                    'sound' => "default",
                                    "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                                    'click_action' => $redirect_URL,
                                    "android_channel_id" => "default-channel-id"
                                ],
                                "data" => [
                                    'title' => $notification_content->subject,
                                    'body'  => $notification_content->content,
                                    'type' => "reminder_notification"
                                ],
                                "priority" => "high"
                            ];
                            $dataString = $data;
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
                            $result = curl_exec($ch);
                            // Log::info($result);
                            curl_close($ch);
                        }
                    }
                }
                DB::disconnect($database_name);
                // Log::info("checking cart end: {$database_name}!");
            } else {
                DB::disconnect($database_name);
                // Log::info("checking cart  end: {$database_name}!");
            }
        }
    }
}
