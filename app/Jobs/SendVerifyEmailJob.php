<?php

namespace App\Jobs;
use App;
//use Mail;
use Config;
use App\Models\Client;
use App\Mail\VerifyEmailSend;
use Illuminate\Bus\Queueable;
use App\Models\ClientPreference;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Mail;
use Log;
class SendVerifyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details){
       
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        $email = new VerifyEmailSend($this->details);
        Mail::to($this->details['email'])->send($email);
    }
}
