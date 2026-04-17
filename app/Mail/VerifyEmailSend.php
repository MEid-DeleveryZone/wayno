<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmailSend extends Mailable{
    use Queueable, SerializesModels;
    protected $mailData;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData){
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->view('email.verify')->from($this->mailData['mail_from'])->subject($this->mailData['subject'])->with('mailData', $this->mailData);
    }
}
