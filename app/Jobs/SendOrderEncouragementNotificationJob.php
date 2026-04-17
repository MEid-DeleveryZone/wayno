<?php

namespace App\Jobs;

use App\Models\Language;
use App\Models\UserDevice;
use App\Models\NotificationTemplate;
use App\Services\FirebaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendOrderEncouragementNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 3600;

    protected $template_identifier;

    /** @var NotificationTemplate|null */
    protected $notificationTemplate;

    protected $fallbackTitle;

    protected $fallbackBody;

    protected $englishLanguageId = 1;

    /**
     * Create a new job instance.
     *
     * @param string|null $template_identifier Template ID or slug to use
     */
    public function __construct($template_identifier = null)
    {
        $this->template_identifier = $template_identifier ?: 'place-order-reminder';
        
        // Set queue name for better tracking
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->englishLanguageId = Language::where('sort_code', 'en')->value('id') ?? 1;
            $this->loadNotificationTemplate();

            $devices = $this->getUserDevices();

            if ($devices->isEmpty()) {
                Log::info('No devices found with FCM tokens');
                return;
            }

            $this->sendNotificationsInBatches($devices);

        } catch (\Exception $e) {
            Log::error('Order encouragement notification job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->fail($e);
            throw $e;
        }
    }

    /**
     * Load notification template (translations resolved per user in localizedTitleAndBody).
     */
    protected function loadNotificationTemplate()
    {
        $this->fallbackTitle = "Don't wait too much!";
        $this->fallbackBody = "Place your order before it's too late";
        $this->notificationTemplate = null;

        $query = NotificationTemplate::query()->with('translations');

        if (is_numeric($this->template_identifier)) {
            $template = $query->where('id', $this->template_identifier)->first();
        } else {
            $template = $query->where('slug', $this->template_identifier)->first();
        }

        if ($template) {
            $this->notificationTemplate = $template;
        }
    }

    /**
     * Title and body for one device, using user's saved language_id (same rules as helpers push flow).
     *
     * @return array{title: string, body: string}
     */
    protected function localizedTitleAndBody(UserDevice $device): array
    {
        if (! $this->notificationTemplate) {
            return [
                'title' => $this->fallbackTitle,
                'body' => $this->fallbackBody,
            ];
        }

        $userLanguageId = $this->englishLanguageId;
        if ($device->user && ! empty($device->user->language_id)) {
            $userLanguageId = (int) $device->user->language_id;
        }

        $translations = $this->notificationTemplate->translations;
        $translation = $translations->firstWhere('language_id', $userLanguageId);

        if (! $translation) {
            $translation = $translations->firstWhere('language_id', $this->englishLanguageId);
        }

        if (! $translation) {
            $translation = $translations->first();
        }

        if (! $translation) {
            return [
                'title' => $this->fallbackTitle,
                'body' => $this->fallbackBody,
            ];
        }

        $title = filled($translation->subject) ? $translation->subject : $this->fallbackTitle;
        $body = filled($translation->content) ? $translation->content : $this->fallbackBody;

        return ['title' => $title, 'body' => $body];
    }

    /**
     * User devices with FCM tokens and user relation (for language_id).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, UserDevice>
     */
    protected function getUserDevices()
    {
        return UserDevice::query()
            ->with('user')
            ->whereNotNull('fcm_token')
            ->whereRaw('fcm_token != ""')
            ->get();
    }

    /**
     * Send notifications in batches to manage memory and timeout
     *
     * @param \Illuminate\Support\Collection<int, UserDevice>|\Illuminate\Database\Eloquent\Collection $devices
     * @return void
     */
    protected function sendNotificationsInBatches($devices)
    {
        $batch_size = 100; // Send in batches of 100
        $batches = $devices->chunk($batch_size);
        
        $total_sent = 0;
        $total_failed = 0;

        foreach ($batches as $batch_index => $batch) {
            $batch_result = $this->sendBatchNotifications($batch);

            $total_sent += $batch_result['sent'];
            $total_failed += $batch_result['failed'];

            if ($batch_index < $batches->count() - 1) {
                sleep(1);
            }
        }
    }

    /**
     * Send notifications to a batch of devices (per-device locale).
     *
     * @param \Illuminate\Support\Collection<int, UserDevice> $batch_devices
     * @return array{sent: int, failed: int}
     */
    protected function sendBatchNotifications($batch_devices)
    {
        $fcm = FirebaseService::connect();

        $sent_count = 0;
        $failed_count = 0;

        foreach ($batch_devices as $device) {
            $payload = $this->localizedTitleAndBody($device);
            $title = $payload['title'];
            $body = $payload['body'];

            $notification = Notification::fromArray([
                'title' => $title,
                'body' => $body,
            ]);

            $data = [
                'title' => $title,
                'body' => $body,
                'type' => 'order_encouragement',
            ];

            try {
                $message_obj = CloudMessage::withTarget('token', $device->fcm_token)
                    ->withNotification($notification)
                    ->withData($data)
                    ->withAndroidConfig([
                        'notification' => [
                            'channel_id' => 'default-channel-id',
                        ],
                    ]);

                $fcm->send($message_obj);
                $sent_count++;
            } catch (\Exception $e) {
                $failed_count++;
                Log::error('Error sending notification to device', [
                    'device_token' => substr((string) $device->fcm_token, 0, 20) . '...',
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return ['sent' => $sent_count, 'failed' => $failed_count];
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return ['notification', 'order-encouragement', 'template:' . $this->template_identifier];
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error('SendOrderEncouragementNotificationJob failed permanently', [
            'template' => $this->template_identifier,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
