<?php

namespace App\Mail;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\ClientCurrency;
use App\Models\ClientPreference;
use App\Models\LoyaltyCard;
use App\Models\Order;
use App\Models\SubscriptionInvoicesUser;
use App\Models\UserAddress;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use DB;
use Carbon\Carbon;
use Session;

class RefferalMail extends Mailable{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data){
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        // dd($this->mailData['user_address']);
        return $this->view('email.refferalcode')->from($this->data['mail_from'])->subject($this->data['subject']);
       // return $this->view('email.refferalcode')->with('data', $this->data);
    }
}
