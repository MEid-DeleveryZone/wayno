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

class OrderSuccessEmail extends Mailable{
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
        return $this->view('email.orderSuccesseEmail')->from($this->mailData['mail_from'])->subject($this->mailData['subject'])->with('mailData', $this->mailData);
    }
}
