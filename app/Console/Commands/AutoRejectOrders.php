<?php

namespace App\Console\Commands;

use App\Models\Client;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\ClientPreference;
use App\Models\OrderVendor;
use App\Models\Vendor;
use App\Models\UserDevice;
use App\Models\NotificationTemplate;
use App\Models\AutoRejectOrderCron;
use Log;
use Carbon\Carbon;
use App\Models\Order;

class AutoRejectOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:reject_order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto reject order after a fixed interval of time';

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
        $intervalTime = Carbon::now();
        $databases = AutoRejectOrderCron::where('auto_reject_time', '<=', $intervalTime)->groupBy('database_name')->get();
        foreach ($databases as $client) {
            $database_name = 'royo_' . $client->database_name;
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
                    'username' => $client->database_username,
                    'password' => $client->database_password,
                    'collation' => 'utf8mb4_unicode_ci',
                    'driver' => env('DB_CONNECTION', 'mysql'),
                ];
                Config::set("database.connections.$database_name", $default);
                // DB::setDefaultConnection($database_name);
                $client_preferences = ClientPreference::on($database_name)->first();
                $notification_content = NotificationTemplate::on($database_name)->where(['id' => 6])->first();
                $selected_database_orders = AutoRejectOrderCron::where('auto_reject_time', '<=', $intervalTime)->where(['database_name' => $client->database_name])->get();
                foreach ($selected_database_orders as $order_key => $order_value) {
                    $orderVendorDetail = OrderVendor::on($database_name)->find($order_value->order_vendor_id);
                    $orderVendorDetail->order_status_option_id = 3;
                    $orderVendorDetail->save();
                    $orderDetail = Order::on($database_name)->find($orderVendorDetail->order_id);
                    AutoRejectOrderCron::where(['order_vendor_id' => $order_value->order_vendor_id, 'database_name' => $client->database_name])->delete();
                    $devices = UserDevice::on($database_name)->whereNotNull('device_token')->where(['user_id' => $orderVendorDetail->user_id])->pluck('device_token')->toArray();
                    if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
                        $from = $client_preferences->fcm_server_key;
                        $body_content = str_ireplace("{order_id}", "#" . $orderDetail->order_number, $notification_content->content);
                        if ($body_content) {
                            $redirect_URL = "https://" . $client->sub_domain . env('SUBMAINDOMAIN') . "/user/orders";
                            $headers = [
                                'Authorization: key=' . $from,
                                'Content-Type: application/json',
                            ];
                            $data = [
                                "registration_ids" => $devices,
                                "notification" => [
                                    'title' => $notification_content->subject,
                                    'body'  => $body_content,
                                    'sound' => "default",
                                    "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                                    'click_action' => $redirect_URL,
                                    "android_channel_id" => "default-channel-id"
                                ],
                                "data" => [
                                    'title' => $notification_content->subject,
                                    'body'  => $body_content,
                                    'type' => "order_status_change"
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
                            Log::info($result);
                            curl_close($ch);
                        }
                    }
                }
                // DB::disconnect($database_name);
                // Log::info("checking cart end: {$database_name}!");
            } else {
                // DB::disconnect($database_name);
                // Log::info("checking cart  end: {$database_name}!");
            }
        }
    }
}
