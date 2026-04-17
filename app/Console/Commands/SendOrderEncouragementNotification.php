<?php

namespace App\Console\Commands;

use App\Jobs\SendOrderEncouragementNotificationJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendOrderEncouragementNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:encourage-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notification to encourage users to create orders';

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
        $this->info('Starting to send order encouragement notifications...');
        
        // Use default template
        $template_identifier = 'place-order-reminder';

        $this->info("Queueing order encouragement notification job...");
        
        try {
            // Dispatch the job to the queue
            SendOrderEncouragementNotificationJob::dispatch(
                $template_identifier
            );
            
            $this->info("Order encouragement notification job queued successfully!");
            $this->info("Using template: {$template_identifier}");
            $this->line("To process the queue, run: php artisan queue:work");
            
        } catch (\Exception $e) {
            $this->error("Failed to queue notification job: " . $e->getMessage());
            Log::error('Failed to queue order encouragement notifications', [
                'error' => $e->getMessage(),
                'template' => $template_identifier
            ]);
            return 1;
        }
        
        return 0;
    }

}