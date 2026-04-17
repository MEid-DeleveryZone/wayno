<?php

namespace App\Jobs;

use App\Models\Client;
use App\Models\ClientPreference;
use App\Models\UserRefferal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Auth;
use App;
use Config;
use Illuminate\Support\Facades\Mail;

class SendRefferalCodeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $send_mail;
    protected $otp;
    protected $mail_from;
    protected $client_name;
    protected $client_logo;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($send_mail)
    {
        $rae = UserRefferal::where('user_id', Auth::user()->id)->first()->toArray();
        $this->otp = $rae['refferal_code'];
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $this->client_name = $client->name;
            $this->mail_from = $data->mail_from;
            $this->send_mail = $email;
            $this->client_logo = $client->logo['original'];
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sendto = $this->email;
        $client_name = $this->client_name;
        $mail_from = $this->mail_from;
        $email = new OrderSuccessEmail($this->details);
        Mail::to($this->details['email'])->send($email);
            try{
                Mail::send('email.verify',[
                        'customer_name' => "Link from ".Auth::user()->name,
                        'code_text' => 'Register yourself using this refferal code below to get bonus offer',
                        'code' => $this->otp,
                        'logo' => $this->client_logo,
                        'link'=> "http://local.myorder.com/user/register?refferal_code=".$this->otp,
                ],
                function ($message) use($sendto, $client_name, $mail_from) {
                    $message->from($mail_from, $client_name);
                    $message->to($sendto)->subject('OTP to verify account');
                });
                $response['send_email'] = 1;
            }
            catch(\Exception $e){
                return response()->json(['data' => $e->getMessage()]);
            }
    }

    public function setMailDetail($mail_driver, $mail_host, $mail_port, $mail_username, $mail_password, $mail_encryption)
    {
        $config = array(
            'driver' => $mail_driver,
            'host' => $mail_host,
            'port' => $mail_port,
            'encryption' => $mail_encryption,
            'username' => $mail_username,
            'password' => $mail_password,
            'sendmail' => '/usr/sbin/sendmail -bs',
            'pretend' => false,
        );

        Config::set('mail', $config);
        $app = App::getInstance();
        $app->register('Illuminate\Mail\MailServiceProvider');
        return '1';

       // return '2';
    }
}
