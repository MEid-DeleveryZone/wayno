<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_ids;
    protected $order_id;
    protected $order_number;
    protected $order_status_id;
    protected $vendor_id;

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
     * Create a new job instance.
     *
     * @param array $user_ids
     * @param int $order_id
     * @param string $order_number
     * @param int $order_status_id
     * @param int $vendor_id
     */
    public function __construct($user_ids, $order_id, $order_number, $order_status_id, $vendor_id = 0)
    {
        $this->user_ids = $user_ids;
        $this->order_id = $order_id;
        $this->order_number = $order_number;
        $this->order_status_id = $order_status_id;
        $this->vendor_id = $vendor_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('SendPushNotificationJob: Starting push notification', [
                'user_ids' => $this->user_ids,
                'order_id' => $this->order_id,
                'order_status_id' => $this->order_status_id,
                'vendor_id' => $this->vendor_id
            ]);

            // Create a minimal order object with only required data
            $orderData = (object) [
                'id' => $this->order_id,
                'order_number' => $this->order_number
            ];

            // Call the existing push notification function
            $result = sendStatusChangePushNotificationCustomer(
                $this->user_ids,
                $orderData,
                $this->order_status_id,
                $this->vendor_id
            );

            if ($result) {
                Log::info('SendPushNotificationJob: Push notification sent successfully', [
                    'order_id' => $this->order_id,
                    'order_number' => $this->order_number,
                    'user_ids' => $this->user_ids,
                    'order_status_id' => $this->order_status_id,
                    'vendor_id' => $this->vendor_id
                ]);
            } else {
                Log::warning('SendPushNotificationJob: Push notification failed', [
                    'order_id' => $this->order_id,
                    'order_number' => $this->order_number,
                    'user_ids' => $this->user_ids,
                    'order_status_id' => $this->order_status_id,
                    'vendor_id' => $this->vendor_id,
                    'reason' => 'Check logs for sendStatusChangePushNotificationCustomer for specific failure reason'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('SendPushNotificationJob: Exception occurred', [
                'error' => $e->getMessage(),
                'order_id' => $this->order_id,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Re-throw the exception to trigger job retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param \Exception $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        Log::error('SendPushNotificationJob: Job failed after all retries', [
            'error' => $exception->getMessage(),
            'order_id' => $this->order_id,
            'user_ids' => $this->user_ids,
            'order_status_id' => $this->order_status_id,
            'vendor_id' => $this->vendor_id
        ]);
    }
}