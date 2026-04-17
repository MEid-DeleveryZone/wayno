<?php

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Nomenclature;
use App\Models\UserRefferal;
use App\Models\ClientPreference;
use App\Models\NotificationTemplate;
use App\Models\UserDevice;
use App\Models\VendorOrderStatus;
use App\Models\OrderVendor;
use App\Models\Language;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

function changeDateFormate($date, $date_format)
{
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);
}

function pr($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}
function http_check($url)
{
    $return = $url;
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $return = 'http://' . $url;
    }
    return $return;
}

function sendStatusChangePushNotificationCustomer($user_ids, $orderData, $order_status_id, $vendor_id = 0)
{
    try {
        // Validate input parameters
        if (empty($user_ids) || empty($orderData) || empty($order_status_id)) {
            Log::error('sendStatusChangePushNotificationCustomer: Invalid parameters', [
                'user_ids' => $user_ids,
                'orderData' => $orderData,
                'order_status_id' => $order_status_id
            ]);
            return false;
        }

        // Get user devices with FCM tokens and user information
        $userDevices = UserDevice::with('user')
            ->whereNotNull('fcm_token')
            ->whereIn('user_id', $user_ids)
            ->get();

        if ($userDevices->isEmpty()) {
            Log::warning('sendStatusChangePushNotificationCustomer: No devices found for users', [
                'user_ids' => $user_ids,
                'order_id' => $orderData->id ?? null,
                'order_status_id' => $order_status_id
            ]);
            return false;
        }

        // Get client preferences
        $client_preferences = ClientPreference::select('fcm_server_key', 'favicon')->first();
        
        if (empty($client_preferences) || empty($client_preferences->fcm_server_key)) {
            Log::error('sendStatusChangePushNotificationCustomer: FCM server key not configured in client preferences', [
                'order_id' => $orderData->id ?? null,
                'order_status_id' => $order_status_id,
                'has_client_preferences' => !empty($client_preferences),
                'has_fcm_key' => !empty($client_preferences->fcm_server_key ?? null)
            ]);
            return false;
        }

        // Get notification template based on order status and cancellation type
        $notification_template = null;
        $order_vendor = null;
        
        // Check if this is a cancellation (status_id = 3) and get the cancellation type
        if ($order_status_id == 3) {
            $order_vendor = OrderVendor::where('order_id', $orderData->id)
                ->where('vendor_id', $vendor_id)
                ->first();
            
            if ($order_vendor && $order_vendor->cancelled_by) {
                // Use specific templates based on who cancelled the order
                switch ($order_vendor->cancelled_by) {
                    case 1: // Admin cancellation
                        $notification_template = NotificationTemplate::where('slug', 'order-cancelled-by-admin')->first();
                        break;
                    case 2: // Customer cancellation
                        $notification_template = NotificationTemplate::where('id', 6)->first(); // Original rejected template
                        break;
                    case 3: // Dispatcher cancellation
                        $notification_template = NotificationTemplate::where('slug', 'order-cancelled-by-dispatcher')->first();
                        break;
                    default:
                        $notification_template = NotificationTemplate::where('id', 6)->first(); // Default rejected template
                        break;
                }
            } else {
                // Fallback to original rejected template if no cancellation type is set
                $notification_template = NotificationTemplate::where('id', 6)->first();
            }
        } else {
            // Use original template mapping for non-cancellation statuses
            $template_mapping = [
                1 => 4,   // Order Received
                2 => 5,   // Order Accepted
                4 => 7,   // Order Processing
                5 => 8,   // Out of delivery
                6 => 9,   // Order Delivered
                7 => 12,  // Additional status
                8 => 13,  // Additional status
                9 => 11   // Additional status
            ];

            if (isset($template_mapping[$order_status_id])) {
                $notification_template = NotificationTemplate::where('id', $template_mapping[$order_status_id])->first();
            }
        }

        if (!$notification_template) {
            $template_info = 'N/A';
            if ($order_status_id == 3) {
                $template_info = 'Cancellation template lookup failed';
            } else {
                $template_mapping = [
                    1 => 4, 2 => 5, 4 => 7, 5 => 8, 6 => 9, 7 => 12, 8 => 13, 9 => 11
                ];
                $template_info = isset($template_mapping[$order_status_id]) 
                    ? "Template ID {$template_mapping[$order_status_id]} not found" 
                    : "No mapping for status {$order_status_id}";
            }
            
            Log::error('sendStatusChangePushNotificationCustomer: Notification template not found for order status', [
                'order_id' => $orderData->id ?? null,
                'order_status_id' => $order_status_id,
                'vendor_id' => $vendor_id,
                'cancelled_by' => $order_vendor->cancelled_by ?? 'N/A',
                'template_info' => $template_info
            ]);
            return false;
        }

        // Load all translations for the template
        $notification_template->load('translations.language');

        // Get English language ID as default
        $englishLanguage = Language::where('sort_code', 'en')->first();
        $englishLanguageId = $englishLanguage ? $englishLanguage->id : 1; // Fallback to 1 if English not found

        // Get vendor order status for additional data
        $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')
            ->where('order_id', $orderData->id)
            ->where('vendor_id', $vendor_id)
            ->orderBy('id', 'DESC')
            ->first();

        // Connect to Firebase
        try {
            $fcm = FirebaseService::connect();
            //Log::info('Firebase connection established successfully');
        } catch (\Exception $e) {
            Log::error('sendStatusChangePushNotificationCustomer: Failed to connect to Firebase', [
                'order_id' => $orderData->id ?? null,
                'order_status_id' => $order_status_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }

        // Send notifications to all devices
        $success_count = 0;
        $error_count = 0;

        foreach ($userDevices as $userDevice) {
            try {
                // Get user's preferred language (default to English if not available)
                $userLanguageId = $englishLanguageId; // Default to English
                
                // Get user's language preference from the user model
                if ($userDevice->user && !empty($userDevice->user->language_id)) {
                    $userLanguageId = $userDevice->user->language_id;
                }
                
                // Get notification content in user's language with English as fallback
                $notification_content = $notification_template->translations
                    ->where('language_id', $userLanguageId)
                    ->first();
                
                // If no translation found for user's language, fallback to English
                if (!$notification_content) {
                    $notification_content = $notification_template->translations
                        ->where('language_id', $englishLanguageId)
                        ->first();
                }
                
                // If still no translation found, use the first available translation
                if (!$notification_content) {
                    $notification_content = $notification_template->translations->first();
                }
                
                // If no translations exist at all, skip this notification
                if (!$notification_content) {
                    Log::error('No translation found for notification template', [
                        'template_id' => $notification_template->id,
                        'user_language_id' => $userLanguageId
                    ]);
                    continue;
                }

                // Prepare notification content
                $body_content = str_ireplace("{order_id}", "#" . $orderData->order_number, $notification_content->content);

                $notification = Notification::fromArray([
                    'title' => $notification_content->subject,
                    'body'  => $body_content,
                ]);

                $data = [
                    'title' => $notification_content->subject,
                    'body'  => $body_content,
                    "type" => "order_status_change",
                    "sortOrder" => $vendor_order_status->OrderStatusOption->sort_order ?? 0,
                    "orderId" => $orderData->id,
                    "description" => $vendor_order_status->OrderStatusOption->description ?? ''
                ];

                $message = CloudMessage::withTarget('token', $userDevice->fcm_token)
                    ->withNotification($notification)
                    ->withData($data)
                    ->withAndroidConfig([
                        'notification' => [
                            'channel_id' => 'default-channel-id',
                        ]
                    ]);

                $fcm->send($message);
                $success_count++;
                //Log::info('Push notification sent successfully', ['device' => $userDevice->fcm_token]);
            } catch (\Exception $e) {
                $error_count++;
                $statusCode = $e->getCode();

                Log::warning('sendStatusChangePushNotificationCustomer: Failed to send notification to device', [
                    'user_id' => $userDevice->user_id ?? null,
                    'device_id' => $userDevice->id ?? null,
                    'order_id' => $orderData->id ?? null,
                    'status_code' => $statusCode,
                    'error' => $e->getMessage()
                ]);

                if ($statusCode === 404) {
                    // Token is not registered with Firebase - remove it
                    Log::info('Removing invalid FCM token', ['device' => $userDevice->fcm_token]);
                    UserDevice::where('fcm_token', $userDevice->fcm_token)->delete();
                }
            }
        }
        
        if ($success_count == 0 && $error_count > 0) {
            Log::error('sendStatusChangePushNotificationCustomer: All notifications failed', [
                'order_id' => $orderData->id ?? null,
                'order_status_id' => $order_status_id,
                'total_devices' => $userDevices->count(),
                'success_count' => $success_count,
                'error_count' => $error_count
            ]);
        }
        
        return $success_count > 0;
    } catch (\Exception $e) {
        Log::error('Unexpected error in sendStatusChangePushNotificationCustomer', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return false;
    }
}
function getUserDetailViaApi($user)
{
    $user_refferal = UserRefferal::where('user_id', $user->id)->first();
    $client_preference = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
    $data['name'] = $user->name;
    $data['email'] = $user->email;
    $data['source'] = $user->image;
    $data['is_admin'] = $user->is_admin;
    $data['dial_code'] = $user->dial_code;
    $data['auth_token'] =  $user->auth_token;
    $data['phone_number'] = $user->phone_number;
    $data['client_preference'] = $client_preference;
    $data['cca2'] = $user->country ? $user->country->code : '';
    $data['callingCode'] = $user->country ? $user->country->phonecode : '';
    $data['refferal_code'] = $user_refferal ? $user_refferal->refferal_code : '';
    $data['verify_details'] = ['is_email_verified' => $user->is_email_verified, 'is_phone_verified' => $user->is_phone_verified];
    return $data;
}
function getMonthNumber($month_name)
{
    if ($month_name == 'January') {
        return 1;
    } else if ($month_name == 'February') {
        return 2;
    } else if ($month_name == 'March') {
        return 3;
    } else if ($month_name == 'April') {
        return 4;
    } else if ($month_name == 'May') {
        return 5;
    } else if ($month_name == 'June') {
        return 6;
    } else if ($month_name == 'July') {
        return 7;
    } else if ($month_name == 'August') {
        return 8;
    } else if ($month_name == 'September') {
        return 9;
    } else if ($month_name == 'October') {
        return 10;
    } else if ($month_name == 'November') {
        return 11;
    } else if ($month_name == 'December') {
        return 12;
    }
}
function generateOrderNo($length = 8)
{
    $number = '';
    do {
        for ($i = $length; $i--; $i > 0) {
            $number .= mt_rand(0, 9);
        }
    } while (!empty(DB::table('orders')->where('order_number', $number)->first(['order_number'])));
    return $number;
}
function getNomenclatureName($searchTerm, $plural = true)
{
    $result = Nomenclature::with(['translations' => function ($q) {
        $q->where('language_id', session()->get('customerLanguage'));
    }])->where('label', 'LIKE', "%{$searchTerm}%")->first();
    if ($result) {
        $searchTerm = $result->translations->count() != 0 ? $result->translations->first()->name : ucfirst($searchTerm);
    }
    return $plural ? $searchTerm : rtrim($searchTerm, 's');
}
function convertDateTimeInTimeZone($date, $timezone, $format = 'Y-m-d H:i:s')
{
    $date = Carbon::parse(strtotime($date), 'UTC');
    $date->setTimezone($timezone);
    return $date->format($format);
}
function sendAdminPanelPusherNotification()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://8b6a7b0a-0d24-46da-831c-de6b4e156bad.pushnotifications.pusher.com/publish_api/v1/instances/8b6a7b0a-0d24-46da-831c-de6b4e156bad/publishes',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "interests":["hello"],
            "web":{
                "notification":{
                    "title":"Order Placed",
                    "body":"Hello, Admin You have an order received for processing!"
                }
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer 62BB5283D919D95516F2D1440AC43C937300111584EFFC09A469868CFAB89240'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;
}
